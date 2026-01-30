<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Component;
use Mary\Traits\Toast;

class Network extends Component
{
    use Toast;

    public $radius_isolated_group;
    public $radius_isolated_limit;

    public function mount()
    {
        $this->radius_isolated_group = Setting::getValue('radius_isolated_group', 'ISOLATED');
        $this->radius_isolated_limit = Setting::getValue('radius_isolated_limit', '128k/128k');
    }

    public function save()
    {
        $this->validate([
            'radius_isolated_group' => 'required|string|max:50',
            'radius_isolated_limit' => 'required|string|max:50',
        ]);

        Setting::setValue('radius_isolated_group', $this->radius_isolated_group);
        Setting::setValue('radius_isolated_limit', $this->radius_isolated_limit);

        $this->success('Pengaturan jaringan berhasil disimpan');
    }

    public function render()
    {
        return view('livewire.settings.network');
    }
}
