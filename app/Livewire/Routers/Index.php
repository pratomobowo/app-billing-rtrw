<?php

namespace App\Livewire\Routers;

use App\Models\Router;
use App\Services\MikrotikService;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use WithPagination, Toast;

    public bool $routerModal = false;
    public ?Router $editingRouter = null;

    // Form fields
    public string $name = '';
    public string $ip_address = '';
    public string $username = '';
    public string $password = '';
    public int $port = 8728;

    // Connection Test
    public ?array $testResult = null;
    public bool $isTesting = false;

    public array $headers = [
        ['key' => 'id', 'label' => '#'],
        ['key' => 'name', 'label' => 'Nama Router'],
        ['key' => 'ip_address', 'label' => 'IP Address'],
        ['key' => 'status', 'label' => 'Status'],
        ['key' => 'customers_count', 'label' => 'Pelanggan', 'sortable' => false],
    ];

    public function create()
    {
        $this->reset(['name', 'ip_address', 'username', 'password', 'port', 'editingRouter', 'testResult']);
        $this->routerModal = true;
    }

    public function edit(Router $router)
    {
        $this->editingRouter = $router;
        $this->name = $router->name;
        $this->ip_address = $router->ip_address;
        $this->username = $router->username;
        $this->password = $router->password; // In production, avoid sending back password if encrypted
        $this->port = $router->port;
        $this->testResult = null;
        $this->routerModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'ip_address' => 'required|ipv4',
            'username' => 'required|string',
            'password' => 'required|string',
            'port' => 'required|integer',
        ]);

        Router::updateOrCreate(
            ['id' => $this->editingRouter?->id],
            [
                'name' => $this->name,
                'ip_address' => $this->ip_address,
                'username' => $this->username,
                'password' => $this->password,
                'port' => $this->port,
                'status' => 'offline', // Default status
            ]
        );

        $this->success($this->editingRouter ? 'Router berhasil diperbarui' : 'Router berhasil ditambahkan');
        $this->routerModal = false;
    }

    public function testConnection(MikrotikService $service)
    {
        $this->isTesting = true;
        
        $result = $service->testConnection(
            $this->ip_address,
            $this->username,
            $this->password,
            $this->port
        );

        if ($result) {
            $this->testResult = $result;
            $this->success('Koneksi Berhasil!', position: 'toast-bottom');
            if ($this->editingRouter) {
                // Update status if saved
                $this->editingRouter->update(['status' => 'online']);
            }
        } else {
            $this->testResult = null;
            $this->error('Koneksi Gagal. Cek IP, User, Password.', position: 'toast-bottom');
        }

        $this->isTesting = false;
    }
    
    public function delete(Router $router)
    {
        $router->delete();
        $this->success('Router berhasil dihapus');
    }

    public function render()
    {
        return view('livewire.routers.index', [
            'routers' => Router::withCount('customers')->paginate(10)
        ]);
    }
}
