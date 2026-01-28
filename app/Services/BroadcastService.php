<?php

namespace App\Services;

use App\Models\GowaDevice;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Support\Collection;
use Exception;
use Illuminate\Support\Facades\Log;

class BroadcastService
{
    protected $gowaService;

    public function __construct(GowaService $gowaService)
    {
        $this->gowaService = $gowaService;
    }

    /**
     * Get active device to send messages. 
     * For now, picks the first authenticated device.
     */
    protected function getActiveDevice(): ?GowaDevice
    {
        return GowaDevice::where('status', '!=', 'disconnected')->first();
    }

    public function broadcast(Collection $recipients, string $messageTemplate, ?GowaDevice $device = null): array
    {
        $device = $device ?? $this->getActiveDevice();
        if (!$device) {
            throw new Exception("Tidak ada device WhatsApp yang terhubung.");
        }

        $sentCount = 0;
        $failCount = 0;

        foreach ($recipients as $recipient) {
            // Recipient can be Customer (has phone) or Invoice (has customer->phone)
            $phone = '';
            $name = '';
            $params = [];

            if ($recipient instanceof Customer) {
                $phone = $recipient->whatsapp;
                $name = $recipient->name;
                $params = [
                    '{name}' => $name,
                    '{address}' => $recipient->address,
                ];
            } elseif ($recipient instanceof Invoice) {
                $phone = $recipient->customer->whatsapp ?? '';
                $name = $recipient->customer->name ?? '';
                $params = [
                    '{name}' => $name,
                    '{tagihan}' => 'Rp ' . number_format($recipient->amount, 0, ',', '.'),
                    '{bulan}' => $recipient->created_at->format('F Y'),
                    '{duedate}' => $recipient->due_date ? $recipient->due_date->format('d M Y') : '-',
                    '{invoice_number}' => $recipient->invoice_number,
                ];
            }

            if (empty($phone)) {
                $failCount++;
                continue;
            }

            // Replace placeholders
            $finalMessage = str_replace(array_keys($params), array_values($params), $messageTemplate);

            try {
                $this->gowaService->sendMessage($device, $phone, $finalMessage);
                $sentCount++;
                // Add small delay to prevent block if needed, though Gowa v8 might handle it
                usleep(500000); // 0.5s delay
            } catch (Exception $e) {
                Log::error("Broadcast failed for $phone: " . $e->getMessage());
                $failCount++;
            }
        }

        return ['sent' => $sentCount, 'failed' => $failCount];
    }
}
