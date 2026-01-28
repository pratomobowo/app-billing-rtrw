<?php

namespace App\Livewire\Radius;

use App\Models\Radius\RadAcct;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function render()
    {
        // Simple monitor: show most recent sessions or active sessions
        $sessions = RadAcct::query()
            ->when($this->search, fn($q) => $q->where('username', 'like', "%{$this->search}%"))
            ->orderBy('radacctid', 'desc')
            ->paginate(10);

        return view('livewire.radius.index', [
            'sessions' => $sessions
        ]);
    }
}
