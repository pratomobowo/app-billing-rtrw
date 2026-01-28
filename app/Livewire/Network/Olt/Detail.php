<?php

namespace App\Livewire\Network\Olt;

use App\Models\Olt;
use App\Models\Onu;
use App\Services\OltService;
use Livewire\Component;
use Mary\Traits\Toast;

class Detail extends Component
{
    use Toast;
    
    public Olt $olt;

    public function mount(Olt $olt)
    {
        $this->olt = $olt;
    }

    public function refreshSignal(OltService $service, $onuId)
    {
        $onu = Onu::find($onuId);
        if ($onu) {
            $signal = $service->checkSignal($this->olt, $onu->serial_number);
            $onu->update(['signal' => $signal, 'last_check' => now()]);
            $this->success("Signal refreshed: {$signal} dBm");
        }
    }
    
    public function refreshAll(OltService $service)
    {
        $service->refreshAll($this->olt);
        $this->success('All ONUs refreshed');
    }

    public function render()
    {
        return view('livewire.network.olt.detail', [
            'onus' => $this->olt->onus()->get()
        ]);
    }
}
