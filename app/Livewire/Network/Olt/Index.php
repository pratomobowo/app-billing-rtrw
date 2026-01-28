<?php

namespace App\Livewire\Network\Olt;

use App\Models\Olt;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use WithPagination, Toast;

    public bool $oltModal = false;
    public bool $detailModal = false;
    public $editingOlt = null;
    public $selectedOlt = null;
    
    // Form
    public $name, $ip_address, $type = 'ZTE', $username, $password, $port = 23;

    public function create()
    {
        $this->reset(['name', 'ip_address', 'type', 'username', 'password', 'port', 'editingOlt']);
        $this->oltModal = true;
    }

    public function edit(Olt $olt)
    {
        $this->editingOlt = $olt;
        $this->name = $olt->name;
        $this->ip_address = $olt->ip_address;
        $this->type = $olt->type;
        $this->username = $olt->username;
        $this->password = $olt->password;
        $this->port = $olt->port;
        $this->oltModal = true;
    }

    public function showDetail(Olt $olt)
    {
        $this->selectedOlt = $olt;
        $this->detailModal = true;
    }

    public function refreshSignal(\App\Services\OltService $service, $onuId)
    {
        $onu = \App\Models\Onu::find($onuId);
        if ($onu && $this->selectedOlt) {
            $signal = $service->checkSignal($this->selectedOlt, $onu->serial_number);
            $onu->update(['signal' => $signal, 'last_check' => now()]);
            $this->success("Signal refreshed: {$signal} dBm");
        }
    }
    
    public function refreshAll(\App\Services\OltService $service)
    {
        if ($this->selectedOlt) {
            $service->refreshAll($this->selectedOlt);
            $this->success('All ONUs refreshed');
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required',
            'ip_address' => 'required|ip',
            'type' => 'required',
        ]);

        Olt::updateOrCreate(
            ['id' => $this->editingOlt?->id],
            [
                'name' => $this->name,
                'ip_address' => $this->ip_address,
                'type' => $this->type,
                'username' => $this->username,
                'password' => $this->password,
                'port' => $this->port,
            ]
        );

        $this->success('Data OLT berhasil disimpan');
        $this->oltModal = false;
    }

    public function render()
    {
        return view('livewire.network.olt.index', [
            'olts' => Olt::withCount('onus')->paginate(10),
            'headers' => [
                ['key' => 'id', 'label' => '#'],
                ['key' => 'name', 'label' => 'Nama OLT'],
                ['key' => 'ip_address', 'label' => 'IP Address'],
                ['key' => 'type', 'label' => 'Tipe'],
                ['key' => 'onus_count', 'label' => 'Total ONU'],
            ]
        ]);
    }
}
