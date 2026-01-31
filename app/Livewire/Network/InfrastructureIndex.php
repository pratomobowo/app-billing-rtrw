<?php

namespace App\Livewire\Network;

use Livewire\Component;

class InfrastructureIndex extends Component
{
    public string $activeTab = 'olt';

    public function render()
    {
        return view('livewire.network.infrastructure-index', [
            'oltCount' => \App\Models\Olt::count(),
            'odcCount' => \App\Models\Odc::count(),
            'odpCount' => \App\Models\Odp::count(),
        ])->layout('layouts.app')->title('Manajemen Infrastruktur');
    }
}
