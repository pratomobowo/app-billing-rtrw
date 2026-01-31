<?php

namespace App\Livewire\Network;

use App\Models\Area;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class AreaIndex extends Component
{
    use WithPagination, Toast;

    public bool $areaModal = false;
    public ?Area $editingArea = null;

    public string $name = '';
    public string $code = '';
    public string $description = '';

    public function create()
    {
        $this->reset(['name', 'code', 'description', 'editingArea']);
        $this->areaModal = true;
    }

    public function edit(Area $area)
    {
        $this->editingArea = $area;
        $this->name = $area->name;
        $this->code = $area->code;
        $this->description = $area->description ?? '';
        $this->areaModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:areas,code,' . $this->editingArea?->id,
        ]);

        Area::updateOrCreate(
            ['id' => $this->editingArea?->id],
            [
                'name' => $this->name,
                'code' => $this->code,
                'description' => $this->description,
            ]
        );

        $this->success($this->editingArea ? 'Wilayah berhasil diperbarui' : 'Wilayah berhasil ditambahkan');
        $this->areaModal = false;
    }

    public function delete(Area $area)
    {
        if ($area->customers()->count() > 0) {
            $this->error('Tidak dapat menghapus wilayah yang memiliki pelanggan aktif');
            return;
        }

        $area->delete();
        $this->success('Wilayah berhasil dihapus');
    }

    public function render()
    {
        return view('livewire.network.area-index', [
            'areas' => Area::withCount('customers')->paginate(10),
        ])->layout('layouts.app')->title('Manajemen Wilayah');
    }
}
