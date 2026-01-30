<?php

namespace App\Livewire\Monitoring;

use App\Models\Router;
use App\Services\MikrotikService;
use Livewire\Component;
use Mary\Traits\Toast;

class Logs extends Component
{
    use Toast;

    public $router_id;
    public $logs = [];
    public $limit = 50;

    public function mount()
    {
        $this->router_id = Router::first()?->id;
        if ($this->router_id) {
            $this->refreshLogs(new MikrotikService());
        }
    }

    public function refreshLogs(MikrotikService $service)
    {
        if (!$this->router_id) return;

        $router = Router::find($this->router_id);
        if ($router) {
            $this->logs = $service->getLogs($router, $this->limit);
        }
    }

    public function render()
    {
        return view('livewire.monitoring.logs', [
            'routers' => Router::all(),
            'headers' => [
                ['key' => 'time', 'label' => 'Waktu', 'class' => 'w-32'],
                ['key' => 'topics', 'label' => 'Topik', 'class' => 'w-48'],
                ['key' => 'message', 'label' => 'Pesan'],
            ]
        ]);
    }
}
