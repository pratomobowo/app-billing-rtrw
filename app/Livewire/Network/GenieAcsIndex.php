<?php

namespace App\Livewire\Network;

use App\Services\GenieAcsService;
use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class GenieAcsIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function render(GenieAcsService $genieAcs)
    {
        $devices = [];
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(5)
                ->get(config('app.url') . ':7557/devices'); // Assuming port 7557 on same host or using setting
            
            // Actually use the service:
            $devicesResponse = \Illuminate\Support\Facades\Http::timeout(5)
                ->get(rtrim(\App\Models\Setting::getValue('genieacs_url', 'http://localhost:7557'), '/') . '/devices');
            
            if ($devicesResponse->successful()) {
                $devices = $devicesResponse->json();
            }
        } catch (\Exception $e) {
            // Log or ignore
        }

        if ($this->search) {
            $devices = array_filter($devices, function($d) {
                return str_contains(strtolower($d['_serialNumber'] ?? ''), strtolower($this->search));
            });
        }
        
        return view('livewire.network.genie-acs-index', [
            'devices' => $devices,
        ])->layout('layouts.app')->title('Daftar Modem TR-069');
    }
}
