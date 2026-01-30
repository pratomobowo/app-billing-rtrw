<?php

namespace App\Livewire\Hotspot;

use App\Models\Router;
use App\Models\VoucherProfile;
use App\Services\VoucherService;
use Livewire\Component;
use Mary\Traits\Toast;

class VoucherProfiles extends Component
{
    use Toast;

    public bool $profileModal = false;
    public ?VoucherProfile $editingProfile = null;

    // Form fields
    public string $name = '';
    public string $price = '';
    public string $bandwidth_limit = '1M/1M';
    public string $validity = '1d';
    public int $shared_users = 1;

    public function create()
    {
        $this->reset(['name', 'price', 'bandwidth_limit', 'validity', 'shared_users', 'editingProfile']);
        $this->profileModal = true;
    }

    public function edit(VoucherProfile $profile)
    {
        $this->editingProfile = $profile;
        $this->name = $profile->name;
        $this->price = $profile->price;
        $this->bandwidth_limit = $profile->bandwidth_limit;
        $this->validity = $profile->validity;
        $this->shared_users = $profile->shared_users;
        $this->profileModal = true;
    }

    public function save(VoucherService $service)
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'bandwidth_limit' => 'required|string',
            'validity' => 'required|string',
            'shared_users' => 'required|integer|min:1',
        ]);

        $profile = VoucherProfile::updateOrCreate(
            ['id' => $this->editingProfile?->id],
            [
                'name' => $this->name,
                'price' => $this->price,
                'bandwidth_limit' => $this->bandwidth_limit,
                'validity' => $this->validity,
                'shared_users' => $this->shared_users,
            ]
        );

        // Sync to all Routers (or we could select routers)
        $routers = Router::all();
        foreach ($routers as $router) {
            if (!$service->syncProfile($router, $profile)) {
                $this->error("Gagal sinkron profil ke Mikrotik {$router->name}", position: 'toast-bottom');
            }
        }

        $this->success($this->editingProfile ? 'Profil berhasil diperbarui' : 'Profil berhasil dibuat');
        $this->profileModal = false;
    }

    public function delete(VoucherProfile $profile, VoucherService $service)
    {
        // Delete from all routers
        $routers = Router::all();
        foreach ($routers as $router) {
            if (!$service->deleteProfile($router, $profile->name)) {
                $this->warning("Gagal hapus profil di Mikrotik {$router->name}", position: 'toast-bottom');
            }
        }

        $profile->delete();
        $this->success('Profil dihapus');
    }

    public function render()
    {
        return view('livewire.hotspot.voucher-profiles', [
            'profiles' => VoucherProfile::all(),
            'headers' => [
                ['key' => 'id', 'label' => '#'],
                ['key' => 'name', 'label' => 'Nama Profil'],
                ['key' => 'bandwidth_limit', 'label' => 'Limit'],
                ['key' => 'validity', 'label' => 'Masa Aktif'],
                ['key' => 'price', 'label' => 'Harga'],
                ['key' => 'shared_users', 'label' => 'Shared Users'],
            ]
        ]);
    }
}
