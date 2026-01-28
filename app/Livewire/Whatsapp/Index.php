<?php

namespace App\Livewire\Whatsapp;

use App\Models\GowaDevice;
use App\Services\GowaService;
use Livewire\Component;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast;

    public bool $deviceModal = false;
    public string $device_id = '';
    public string $api_key = '';
    public string $test_phone = '';
    public string $test_message = 'Test message from Billing App';
    
    public ?string $qrCodeUrl = null;

    public function mount()
    {
        // For starter, just support 1 device per user or global
    }

    public function createDevice(GowaService $service)
    {
        $this->validate([
            'device_id' => 'required|string|unique:gowa_devices,device_id',
            'api_key' => 'nullable|string',
        ]);

        try {
            // Register to Gowa Server first
            // $service->createDevice($this->device_id, $this->api_key); // Uncomment if we need to auto-register on Gowa

            GowaDevice::create([
                'user_id' => auth()->id() ?? 1,
                'device_id' => $this->device_id,
                'api_key' => $this->api_key ?? 'none',
                'status' => 'disconnected'
            ]);

            $this->success('Device berhasil ditambahkan');
            $this->deviceModal = false;
            $this->reset(['device_id', 'api_key']);
        } catch (\Exception $e) {
            $this->error('Gagal menambahkan device: ' . $e->getMessage());
        }
    }

    public function getQr(GowaService $service, $deviceId)
    {
        $device = GowaDevice::find($deviceId);
        try {
            $qr = $service->getQrCode($device);
            if ($qr) {
                $this->qrCodeUrl = $qr; // base64 image
            } else {
                $this->warning('Tidak ada QR Code. Mungkin sudah terkoneksi?');
            }
        } catch (\Exception $e) {
            $this->error('Error getting QR: ' . $e->getMessage());
        }
    }
    
    public function checkStatus(GowaService $service, $deviceId)
    {
        $device = GowaDevice::find($deviceId);
        try {
            $status = $service->getStatus($device);
            // Assuming status structure
            $deviceStatus = $status['data']['status'] ?? 'unknown'; // Adjust based on real API response
            
            $device->update(['status' => $deviceStatus]);
            $this->success('Status diperbarui: ' . $deviceStatus);
            $this->qrCodeUrl = null; // Clear QR if checked
        } catch (\Exception $e) {
            $this->error('Gagal cek status: ' . $e->getMessage());
        }
    }

    public function sendTest(GowaService $service, $deviceId)
    {
        $this->validate(['test_phone' => 'required', 'test_message' => 'required']);
        $device = GowaDevice::find($deviceId);
        
        try {
            $service->sendMessage($device, $this->test_phone, $this->test_message);
            $this->success('Pesan test terkirim!');
        } catch (\Exception $e) {
            $this->error('Gagal kirim pesan: ' . $e->getMessage());
        }
    }

    public function delete(GowaDevice $device)
    {
        $device->delete();
        $this->success('Device dihapus');
    }

    public function render()
    {
        return view('livewire.whatsapp.index', [
            'devices' => GowaDevice::all()
        ]);
    }
}
