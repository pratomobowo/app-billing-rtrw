<?php

namespace App\Livewire\Hotspot;

use App\Models\Router;
use App\Models\Voucher;
use App\Models\VoucherProfile;
use App\Services\VoucherService;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Illuminate\Support\Str;

class VoucherGenerator extends Component
{
    use WithPagination, Toast;

    public string $search = '';
    public bool $generatorModal = false;

    // Generator fields
    public ?int $router_id = null;
    public ?int $voucher_profile_id = null;
    public int $count = 10;
    public string $comment = '';

    public function create()
    {
        $this->reset(['router_id', 'voucher_profile_id', 'count', 'comment']);
        $this->generatorModal = true;
    }

    public function generate(VoucherService $service)
    {
        $this->validate([
            'router_id' => 'required|exists:routers,id',
            'voucher_profile_id' => 'required|exists:voucher_profiles,id',
            'count' => 'required|integer|min:1|max:100',
        ]);

        $profile = VoucherProfile::find($this->voucher_profile_id);
        $router = Router::find($this->router_id);

        for ($i = 0; $i < $this->count; $i++) {
            // Generate unique 6-8 char code
            $code = strtoupper(Str::random(6));
            
            // Check uniqueness in DB (highly likely unique for 6 chars, but safe)
            while (Voucher::where('code', $code)->exists()) {
                $code = strtoupper(Str::random(6));
            }

            $voucher = Voucher::create([
                'voucher_profile_id' => $profile->id,
                'router_id' => $router->id,
                'code' => $code,
                'status' => 'unused',
                'comment' => $this->comment,
            ]);

            // Sync to Mikrotik
            $service->syncVoucher($voucher);
        }

        $this->success("{$this->count} voucher berhasil di-generate");
        $this->generatorModal = false;
    }

    public function delete(Voucher $voucher, VoucherService $service)
    {
        $service->deleteVoucher($voucher);
        $voucher->delete();
        $this->success('Voucher berhasil dihapus');
    }

    public function render()
    {
        $vouchers = Voucher::with(['profile', 'router'])
            ->when($this->search, fn($q) => $q->where('code', 'like', "%{$this->search}%"))
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('livewire.hotspot.voucher-generator', [
            'vouchers' => $vouchers,
            'routers' => Router::all(),
            'profiles' => VoucherProfile::all(),
        ]);
    }
}
