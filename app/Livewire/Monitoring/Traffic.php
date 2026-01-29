<?php

namespace App\Livewire\Monitoring;

use App\Models\Router;
use App\Services\TrafficService;
use Livewire\Component;

class Traffic extends Component
{
    public $router_id;
    public $interface = 'ether1-gateway';
    
    // Chart Data
    public $labels = [];
    public $rxData = [];
    public $txData = [];

    public function mount()
    {
        $this->router_id = Router::first()?->id;
    }

    public function updateChart(TrafficService $service)
    {
        if (!$this->router_id) return;
        
        $router = Router::find($this->router_id);
        if (!$router) return;

        $traffic = $service->getInterfaceTraffic($router, $this->interface);
        
        // Keep last 20 points
        $now = now()->format('H:i:s');
        
        $this->dispatch('traffic-update', 
            label: $now,
            rx: (int)$traffic['rx'],
            tx: (int)$traffic['tx']
        );
    }

    public function render(TrafficService $service)
    {
        $routers = Router::all();
        $interfaces = [];
        
        if ($this->router_id) {
            $router = Router::find($this->router_id);
            if ($router) {
                $interfaces = $service->getInterfaces($router);
            }
        }

        return view('livewire.monitoring.traffic', [
            'routers' => $routers,
            'interfaces' => $interfaces
        ]);
    }
}
