<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class Application extends Component
{
    use Toast, WithFileUploads;

    public $app_name;
    public $company_name;
    public $company_address;
    public $company_phone;
    public $logo;
    public $current_logo;

    public function mount()
    {
        $this->app_name = Setting::getValue('app_name');
        $this->company_name = Setting::getValue('company_name');
        $this->company_address = Setting::getValue('company_address');
        $this->company_phone = Setting::getValue('company_phone');
        $this->current_logo = Setting::getValue('app_logo');
    }

    public function save()
    {
        $this->validate([
            'app_name' => 'required|string|max:50',
            'company_name' => 'required|string|max:100',
            'logo' => 'nullable|image|max:1024', // 1MB Max
        ]);

        Setting::setValue('app_name', $this->app_name);
        Setting::setValue('company_name', $this->company_name);
        Setting::setValue('company_address', $this->company_address);
        Setting::setValue('company_phone', $this->company_phone);

        if ($this->logo) {
            $path = $this->logo->store('logos', 'public');
            Setting::setValue('app_logo', '/storage/' . $path);
            $this->current_logo = '/storage/' . $path;
        }

        $this->success('Pengaturan berhasil disimpan');
        
        // Refresh page to update header/layout
        return redirect('/settings/application');
    }

    public function render()
    {
        return view('livewire.settings.application');
    }
}
