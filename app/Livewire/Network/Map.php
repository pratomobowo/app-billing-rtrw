<?php

namespace App\Livewire\Network;

use App\Models\Customer;
use App\Models\Odp;
use Livewire\Component;

class Map extends Component
{
    public function render()
    {
        return view('livewire.network.map', [
            'customers' => Customer::whereNotNull('latitude')->whereNotNull('longitude')->get(),
            'odps' => Odp::all(),
        ]);
    }
}
