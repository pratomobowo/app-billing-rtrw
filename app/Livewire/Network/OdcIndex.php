<?php

namespace App\Livewire\Network;

use App\Models\Odc;
use App\Models\Olt;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class OdcIndex extends Component
{
    use WithPagination, Toast;

    public bool $odcModal = false;
    public ?Odc $editingOdc = null;

    public string $name = '';
    public ?int $olt_id = null;
    public string $latitude = '';
    public string $longitude = '';
    public string $description = '';

    public array $headers = [
        ['key' => 'id', 'label' => '#'],
        ['key' => 'name', 'label' => 'Nama ODC'],
        ['key' => 'olt.name', 'label' => 'Source OLT'],
        ['key' => 'odps_count', 'label' => 'Jumlah ODP'],
        ['key' => 'actions', 'label' => 'Aksi', 'sortable' => false],
    ];

    public function create()
    {
        $this->reset(['name', 'olt_id', 'latitude', 'longitude', 'description', 'editingOdc']);
        $this->odcModal = true;
    }

    public function edit(Odc $odc)
    {
        $this->editingOdc = $odc;
        $this->name = $odc->name;
        $this->olt_id = $odc->olt_id;
        $this->latitude = $odc->latitude ?? '';
        $this->longitude = $odc->longitude ?? '';
        $this->description = $odc->description ?? '';
        $this->odcModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'olt_id' => 'required|exists:olts,id',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
        ]);

        Odc::updateOrCreate(
            ['id' => $this->editingOdc?->id],
            [
                'name' => $this->name,
                'olt_id' => $this->olt_id,
                'latitude' => $this->latitude ?: null,
                'longitude' => $this->longitude ?: null,
                'description' => $this->description,
            ]
        );

        $this->success($this->editingOdc ? 'ODC berhasil diperbarui' : 'ODC berhasil ditambahkan');
        $this->odcModal = false;
    }

    public function delete(Odc $odc)
    {
        $odc->delete();
        $this->success('ODC berhasil dihapus');
    }

    public function render()
    {
        return view('livewire.network.odc-index', [
            'odcs' => Odc::with('olt')->withCount('odps')->paginate(10),
            'olts' => Olt::all(),
        ]);
    }
}
