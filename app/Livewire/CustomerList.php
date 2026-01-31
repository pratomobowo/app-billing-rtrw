<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Router;
use App\Models\Package;
use App\Models\Setting;
use App\Services\RadiusService;
use App\Services\MikrotikService;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Illuminate\Support\Str;

class CustomerList extends Component
{
    use WithPagination, Toast;

    public string $search = '';
    public ?int $odp_id = null;
    public ?int $odp_port = null;
    public bool $customerModal = false;
    public ?Customer $editingCustomer = null;

    // Form Fields
    public string $name = '';
    public string $whatsapp = '';
    public ?int $router_id = null;
    public ?int $package_id = null;
    public string $address = '';
    public string $connection_type = 'pppoe'; // Default
    public string $pppoe_user = '';
    public string $pppoe_pass = '';
    public string $status = 'active';
    public int $due_date = 20; // Default due date
    public $latitude;
    public $longitude;

    public function updatedName($value)
    {
        // Only auto-fill if we are creating a new customer
        if (!$this->editingCustomer) {
            $this->pppoe_user = Str::lower(Str::slug($value, '_'));
        }
    }

    public function create()
    {
        $this->reset(['name', 'whatsapp', 'router_id', 'package_id', 'odp_id', 'odp_port', 'address', 'connection_type', 'pppoe_user', 'pppoe_pass', 'status', 'due_date', 'editingCustomer', 'latitude', 'longitude']);
        
        // Generate random 6-character password for new customer
        $this->pppoe_pass = Str::random(6);
        
        $this->customerModal = true;
    }

    public function edit(Customer $customer)
    {
        $this->editingCustomer = $customer;
        $this->name = $customer->name;
        $this->whatsapp = $customer->whatsapp;
        $this->router_id = $customer->router_id;
        $this->package_id = $customer->package_id;
        $this->address = $customer->address;
        $this->connection_type = $customer->connection_type;
        $this->pppoe_user = $customer->pppoe_user;
        $this->pppoe_pass = $customer->pppoe_pass;
        $this->status = $customer->status;
        $this->due_date = $customer->due_date;
        $this->latitude = $customer->latitude;
        $this->longitude = $customer->longitude;
        $this->customerModal = true;
    }

    public function save(RadiusService $radiusService, MikrotikService $mikrotikService)
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'router_id' => 'required|exists:routers,id',
            'package_id' => 'required|exists:packages,id',
            'pppoe_user' => 'required_if:connection_type,pppoe|string',
            'pppoe_pass' => 'required_if:connection_type,pppoe|string',
            'due_date' => 'required|integer|min:1|max:31',
        ]);

        $isNew = !$this->editingCustomer;
        $oldStatus = $this->editingCustomer?->status;

        $data = [
            'name' => $this->name,
            'whatsapp' => $this->whatsapp,
            'router_id' => $this->router_id,
            'package_id' => $this->package_id,
            'address' => $this->address,
            'connection_type' => $this->connection_type,
            'pppoe_user' => $this->pppoe_user,
            'pppoe_pass' => $this->pppoe_pass,
            'status' => $this->status,
            'due_date' => $this->due_date,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'odp_id' => $this->odp_id,
            'odp_port' => $this->odp_port,
        ];

        $customer = Customer::updateOrCreate(
            ['id' => $this->editingCustomer?->id],
            $data
        );

        // Sync with Radius & Router
        if ($this->connection_type === 'pppoe') {
            $package = Package::find($this->package_id);
            if ($package) {
                // 1. Sync User Credentials
                if (!$radiusService->syncUser($this->pppoe_user, $this->pppoe_pass, $package->name)) {
                    $this->warning('Gagal sinkron data login ke Radius', position: 'toast-bottom');
                }
                
                // 2. Sync Status (Isolated vs Active)
                if (!$radiusService->setUserStatus($this->pppoe_user, $this->status, $package->name)) {
                    $this->warning('Gagal sinkron status ke Radius', position: 'toast-bottom');
                }

                // 3. Sync to Router local PPP
                $router = Router::find($this->router_id);
                if ($router) {
                    // Decide profile based on status
                    $isolatedGroup = Setting::getValue('radius_isolated_group', 'ISOLATED');
                    $profile = ($this->status === 'isolated') ? $isolatedGroup : $package->name;

                    if (!$mikrotikService->syncPppoeUser(
                        $router,
                        $this->pppoe_user,
                        $this->pppoe_pass,
                        $profile,
                        "Customer: " . $this->name
                    )) {
                        $this->error("Gagal sinkron ke Mikrotik {$router->name}", position: 'toast-bottom');
                    }

                    // 4. Kick if status changed to apply immediately
                    if (!$isNew && $oldStatus !== $this->status) {
                        $mikrotikService->kickUser($router, $this->pppoe_user);
                    }
                } else {
                    $this->warning('Router tidak ditemukan untuk sinkronisasi', position: 'toast-bottom');
                }
            } else {
                $this->error('Paket tidak ditemukan', position: 'toast-bottom');
            }
        }

        $this->success($this->editingCustomer ? 'Pelanggan berhasil diperbarui' : 'Pelanggan berhasil ditambahkan');
        $this->customerModal = false;
    }
    
    public function delete(Customer $customer, RadiusService $radiusService, MikrotikService $mikrotikService)
    {
        // Remove from Radius
        $radiusService->deleteUser($customer->pppoe_user);
        
        // Remove from Router
        if ($customer->router) {
            $mikrotikService->deletePppoeUser($customer->router, $customer->pppoe_user);
        }
        
        $customer->delete();
        $this->success('Pelanggan berhasil dihapus');
    }

    public function render()
    {
        $customers = Customer::with(['package', 'router'])
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->paginate(10);

        $headers = [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'name', 'label' => 'Nama Pelanggan'],
            ['key' => 'whatsapp', 'label' => 'WhatsApp'],
            ['key' => 'package.name', 'label' => 'Paket'],
            ['key' => 'router.name', 'label' => 'Router'],
            ['key' => 'status', 'label' => 'Status'],
        ];

        return view('livewire.customers.index', [
            'customers' => $customers,
            'headers' => $headers,
            'routers' => Router::all(),
            'packages' => Package::all(),
            'odps' => \App\Models\Odp::all(),
        ]);
    }
}
