<?php

namespace App\Livewire\Network;

use App\Models\Odp;
use App\Models\Odc;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class OdpIndex extends Component
{
    use WithPagination, Toast;

    public bool $odpModal = false;
    public ?Odp $editingOdp = null;

    public string $name = '';
    public ?int $odc_id = null;
    public int $capacity = 16;
    public string $latitude = '';
    public string $longitude = '';
    public string $description = '';

    public array $headers = [
        ['key' => 'id', 'label' => '#'],
        ['key' => 'name', 'label' => 'Nama ODP'],
        ['key' => 'odc.name', 'label' => 'Source ODC'],
        ['key' => 'filled', 'label' => 'Kapasitas'],
        ['key' => 'actions', 'label' => 'Aksi', 'sortable' => false],
    ];

    public function create()
    {
        $this->reset(['name', 'odc_id', 'capacity', 'latitude', 'longitude', 'description', 'editingOdp']);
        $this->odpModal = true;
    }

    public function edit(Odp $odp)
    {
        $this->editingOdp = $odp;
        $this->name = $odp->name;
        $this->odc_id = $odp->odc_id;
        $this->capacity = $odp->capacity;
        $this->latitude = $odp->latitude ?? '';
        $this->longitude = $odp->longitude ?? '';
        $this->description = $odp->description ?? '';
        $this->odpModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'odc_id' => 'required|exists:odcs,id',
            'capacity' => 'required|integer|min:1',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
        ]);

        Odp::updateOrCreate(
            ['id' => $this->editingOdp?->id],
            [
                'name' => $this->name,
                'odc_id' => $this->odc_id,
                'capacity' => $this->capacity,
                'latitude' => $this->latitude ?: null,
                'longitude' => $this->longitude ?: null,
                'description' => $this->description,
            ]
        );

        $this->success($this->editingOdp ? 'ODP berhasil diperbarui' : 'ODP berhasil ditambahkan');
        $this->odpModal = false;
    }

    public function delete(Odp $odp)
    {
        $odp->delete();
        $this->success('ODP berhasil dihapus');
    }

    public function render()
    {
        return view('livewire.network.odp-index', [
            'odps' => Odp::with(['odc', 'onus'])->paginate(10),
            'odcs' => Odc::all(),
        ]);
    }
}
