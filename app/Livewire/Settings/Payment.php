<?php

namespace App\Livewire\Settings;

use App\Models\PaymentGateway;
use Livewire\Component;
use Mary\Traits\Toast;

class Payment extends Component
{
    use Toast;

    public $gateways;
    public $configs = [];

    public function mount()
    {
        $this->gateways = PaymentGateway::all();
        foreach ($this->gateways as $gateway) {
            $this->configs[$gateway->id] = $gateway->config;
        }
    }

    public function save(int $gatewayId)
    {
        $gateway = PaymentGateway::find($gatewayId);
        $gateway->config = $this->configs[$gatewayId]; // Auto cast to array
        $gateway->save();

        $this->success('Konfigurasi ' . $gateway->name . ' berhasil disimpan');
    }

    public function toggle(int $gatewayId)
    {
        $gateway = PaymentGateway::find($gatewayId);
        $gateway->is_active = !$gateway->is_active;
        $gateway->save();
        
        // Reload to update UI state
        $this->gateways = PaymentGateway::all();
    }

    public function render()
    {
        return view('livewire.settings.payment');
    }
}
