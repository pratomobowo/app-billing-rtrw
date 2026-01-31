<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use App\Services\GenieAcsService;
use Livewire\Component;
use Mary\Traits\Toast;

class ModemControl extends Component
{
    use Toast;

    public Customer $customer;
    public $device = null;
    public bool $loading = false;

    public function mount(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function loadDeviceInfo(GenieAcsService $genieAcs)
    {
        if (!$this->customer->modem_serial_number) {
            return;
        }

        $this->loading = true;
        $this->device = $genieAcs::getDevice($this->customer->modem_serial_number);
        $this->loading = false;

        if (!$this->device) {
            $this->error("Perangkat tidak ditemukan di GenieACS");
        }
    }

    public function reboot(GenieAcsService $genieAcs)
    {
        if (!$this->device) return;

        if ($genieAcs->reboot($this->device['_id'])) {
            $this->success("Perintah Reboot dikirim");
        } else {
            $this->error("Gagal mengirim perintah Reboot");
        }
    }

    public function render()
    {
        return view('livewire.customers.modem-control');
    }
}
