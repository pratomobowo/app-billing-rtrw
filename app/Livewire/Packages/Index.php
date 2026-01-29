<?php

namespace App\Livewire\Packages;

use App\Models\Package;
use App\Models\Router;
use App\Models\Setting;
use App\Services\RadiusService;
use App\Services\MikrotikService;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use WithPagination, Toast;

    public string $search = '';
    public bool $packageModal = false;
    
    public ?Package $editingPackage = null;
    
    // Form fields
    public string $name = '';
    public string $price = '';
    public string $bandwidth_limit = '';
    public string $description = '';

    public array $headers = [
        ['key' => 'id', 'label' => '#'],
        ['key' => 'name', 'label' => 'Nama Paket'],
        ['key' => 'bandwidth_limit', 'label' => 'Bandwidth'],
        ['key' => 'price', 'label' => 'Harga'],
        ['key' => 'description', 'label' => 'Keterangan'],
    ];

    public function create(): void
    {
        $this->reset(['name', 'price', 'bandwidth_limit', 'description', 'editingPackage']);
        $this->packageModal = true;
    }

    public function edit(Package $package): void
    {
        $this->editingPackage = $package;
        $this->name = $package->name;
        $this->price = $package->price;
        $this->bandwidth_limit = $package->bandwidth_limit;
        $this->description = $package->description ?? '';
        $this->packageModal = true;
    }

    public function save(RadiusService $radiusService, MikrotikService $mikrotikService)
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'bandwidth_limit' => 'required|string', // e.g., 10M/10M
            'description' => 'nullable|string', // Keep description validation
        ]);

        Package::updateOrCreate(
            ['id' => $this->editingPackage?->id],
            [
                'name' => $this->name,
                'price' => $this->price,
                'bandwidth_limit' => $this->bandwidth_limit,
                'description' => $this->description,
            ]
        );

        // 1. Sync to Radius
        $radiusService->syncGroup($this->name, $this->bandwidth_limit);

        // 2. Sync to all Routers (PPP Profile)
        $routers = Router::all();
        foreach ($routers as $router) {
            $mikrotikService->syncPppoeProfile($router, $this->name, $this->bandwidth_limit);
        }

        // 3. Also sync Isolated Group Profile to all routers if it doesn't exist
        $isolatedName = Setting::getValue('radius_isolated_group', 'ISOLATED');
        foreach ($routers as $router) {
            // Give isolated group a very low limit if not specified, e.g., 128k/128k
            $mikrotikService->syncPppoeProfile($router, $isolatedName, '128k/128k');
        }

        $this->success($this->editingPackage ? 'Paket berhasil diperbarui' : 'Paket berhasil ditambahkan');
        $this->packageModal = false;
    }

    public function delete(Package $package): void
    {
        $package->delete();
        $this->success('Paket berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.packages.index', [
            'packages' => Package::query()
                ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
                ->paginate(10)
        ]);
    }
}
