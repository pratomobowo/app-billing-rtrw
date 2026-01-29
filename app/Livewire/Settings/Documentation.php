<?php

namespace App\Livewire\Settings;

use Livewire\Component;

class Documentation extends Component
{
    public function render()
    {
        return view('livewire.settings.documentation')->layout('layouts.app');
    }
}
