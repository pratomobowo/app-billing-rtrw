<?php

namespace App\Livewire\Monitoring;

use App\Models\Router;
use App\Services\MikrotikService;
use Livewire\Component;
use Mary\Traits\Toast;

class RouterBoard extends Component
{
    use Toast;

    public $router_id;
    public $resources = null;
    public $logs = [];
    public $limit = 30;
    public $loading = false;

    public function mount()
    {
        $this->router_id = Router::first()?->id;
        if ($this->router_id) {
            $this->refreshData();
        }
    }

    public function refreshData()
    {
        if (!$this->router_id) return;

        $router = Router::find($this->router_id);
        if ($router) {
            $service = new MikrotikService();
            $this->resources = $service->getSystemResource($router);
            $this->logs = $service->getLogs($router, $this->limit);
            
            if (!$this->resources) {
                $this->error("Gagal terhubung ke router {$router->name}", position: 'toast-bottom');
            }
        }
    }

    public function updatedRouterId()
    {
        $this->refreshData();
    }

    public function render()
    {
        return view('livewire.monitoring.router-board', [
            'routers' => Router::all(),
        ]);
    }
}
