<?php

namespace App\Livewire\Monitoring;

use App\Models\Router;
use App\Services\MikrotikService;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class RouterBoard extends Component
{
    use Toast, WithPagination;

    public $router_id;
    public $resources = null;
    public $limit = 100; // Total logs to fetch
    public $perPage = 10; // Logs per page
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
            
            if (!$this->resources) {
                $this->error("Gagal terhubung ke router {$router->name}", position: 'toast-bottom');
            }
        }
    }

    public function getLogsProperty()
    {
        if (!$this->router_id) return collect();

        $router = Router::find($this->router_id);
        if (!$router) return collect();

        $service = new MikrotikService();
        $allLogs = $service->getLogs($router, $this->limit);
        
        return collect($allLogs);
    }

    public function updatedRouterId()
    {
        $this->resetPage();
        $this->refreshData();
    }

    public function render()
    {
        $logs = $this->logs;
        $items = $logs->forPage($this->getPage(), $this->perPage);
        
        $paginatedLogs = new LengthAwarePaginator(
            $items,
            $logs->count(),
            $this->perPage,
            $this->getPage(),
            ['path' => route('generated::CzMCRV6jYfrHUjaN')] // Placeholder, Livewire handles this
        );

        return view('livewire.monitoring.router-board', [
            'routers' => Router::all(),
            'paginatedLogs' => $paginatedLogs
        ]);
    }
}
