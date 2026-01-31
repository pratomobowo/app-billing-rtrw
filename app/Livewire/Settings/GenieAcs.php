<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Component;
use Mary\Traits\Toast;

class GenieAcs extends Component
{
    use Toast;

    public $genieacs_url;
    public $genieacs_timeout;

    public function mount()
    {
        $this->genieacs_url = Setting::getValue('genieacs_url', 'http://localhost:7557');
        $this->genieacs_timeout = Setting::getValue('genieacs_timeout', 10);
    }

    public function save()
    {
        $this->validate([
            'genieacs_url' => 'required|url',
            'genieacs_timeout' => 'required|integer|min:1|max:60',
        ]);

        Setting::setValue('genieacs_url', $this->genieacs_url);
        Setting::setValue('genieacs_timeout', $this->genieacs_timeout);

        $this->success('Pengaturan GenieACS berhasil disimpan');
    }

    public function render()
    {
        return view('livewire.settings.genieacs')->title('Konfigurasi GenieACS');
    }
}
