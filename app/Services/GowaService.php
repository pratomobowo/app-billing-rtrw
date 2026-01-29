<?php

namespace App\Services;

use App\Models\GowaDevice;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Exception;

class GowaService
{
    protected string $baseUrl;

    // Default public server or from config
    public function __construct()
    {
        // Fetch from setting, default to public server if not set
        $this->baseUrl = Setting::getValue('gowa_url', 'https://wa.wiku.my.id'); 
    }

    protected function headers(GowaDevice $device)
    {
        return [
            'Content-Type' => 'application/json',
            'X-Device-ID' => $device->device_id,
            'Authorization' => 'Bearer ' . $device->api_key // If using bearer/basic, adjust as needed. Doc says Basic but usually depends on setup.
        ];
    }

    public function createDevice(string $deviceId, string $apiKey)
    {
        // Register device to Gowa
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            // 'Authorization' => ... if needed for master account
        ])->post("{$this->baseUrl}/devices", [
            'id' => $deviceId,
            'device_id' => $deviceId
        ]);

        return $response->json();
    }

    public function getQrCode(GowaDevice $device)
    {
        $response = Http::withHeaders($this->headers($device))
            ->get("{$this->baseUrl}/app/login"); // Doc says GET /app/login generally returns QR
            
        // Gowa v8 usually returns JSON with qr_link (base64)
        if ($response->successful()) {
            $data = $response->json();
            return $data['results']['qr_link'] ?? null;
        }
        
        throw new Exception("Gagal mengambil QR Code: " . $response->body());
    }

    public function getStatus(GowaDevice $device)
    {
        $response = Http::withHeaders($this->headers($device))
            ->get("{$this->baseUrl}/app/status");

        return $response->json();
    }

    public function sendMessage(GowaDevice $device, string $phone, string $message)
    {
        // Sanitize phone
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '08')) {
            $phone = '62' . substr($phone, 1);
        }

        $response = Http::withHeaders($this->headers($device))
            ->post("{$this->baseUrl}/send/message", [
                'phone' => $phone,
                'message' => $message,
            ]);

        return $response->json();
    }
}
