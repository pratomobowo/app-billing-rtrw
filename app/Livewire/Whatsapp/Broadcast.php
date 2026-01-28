<?php

namespace App\Livewire\Whatsapp;

use App\Models\Customer;
use App\Models\GowaDevice;
use App\Models\Invoice;
use App\Models\Router;
use App\Services\BroadcastService;
use Livewire\Component;
use Mary\Traits\Toast;

class Broadcast extends Component
{
    use Toast;

    public $target = 'unpaid'; // unpaid, all_customers, router
    public $router_id = null;
    public $message = '';
    public $deviceId = null;
    
    // Quick Templates
    public $templates = [
        'tagihan' => "Halo {name},\nIni adalah pengingat tagihan internet bulan {bulan} sebesar {tagihan}. Mohon segera lakukan pembayaran sebelum tanggal {duedate}. Terima kasih.",
        'gangguan' => "Halo {name},\nMohon maaf saat ini sedang ada gangguan jaringan di wilayah Anda. Teknisi kami sedang melakukan perbaikan. Terima kasih atas kesabarannya.",
        'info' => "Halo {name},\nInformasi terbaru dari RT RW Net: ..."
    ];

    public function mount()
    {
        // Default device
        $this->deviceId = GowaDevice::first()?->id;
        $this->setTemplate('tagihan');
    }

    public function setTemplate($type)
    {
        $this->message = $this->templates[$type] ?? '';
    }

    public function getRecipientsProperty()
    {
        if ($this->target === 'unpaid') {
            return Invoice::with('customer')->where('status', 'unpaid')->get();
        } elseif ($this->target === 'all_customers') {
            return Customer::where('status', 'active')->get();
        } elseif ($this->target === 'router' && $this->router_id) {
            return Customer::where('router_id', $this->router_id)->where('status', 'active')->get();
        }
        return collect([]);
    }

    public function send(BroadcastService $service)
    {
        $this->validate([
            'message' => 'required|min:5',
            'deviceId' => 'required'
        ]);

        $recipients = $this->getRecipientsProperty();
        
        if ($recipients->isEmpty()) {
            $this->warning('Tidak ada penerima untuk target yang dipilih.');
            return;
        }

        $device = GowaDevice::find($this->deviceId);
        
        try {
            $result = $service->broadcast($recipients, $this->message, $device);
            $this->success("Broadcast Selesai! Terkirim: {$result['sent']}, Gagal: {$result['failed']}");
        } catch (\Exception $e) {
            $this->error('Gagal Broadcast: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.whatsapp.broadcast', [
            'devices' => GowaDevice::all(),
            'routers' => Router::all(),
            'recipientCount' => $this->getRecipientsProperty()->count()
        ]);
    }
}
