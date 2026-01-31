<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GenieAcsService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim(Setting::getValue('genieacs_url', 'http://localhost:7557'), '/');
        $this->timeout = (int) Setting::getValue('genieacs_timeout', 10);
    }

    /**
     * Fetch device details by serial number
     */
    public function getDevice(string $serialNumber)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/devices", [
                    'query' => json_encode(['_serialNumber' => $serialNumber])
                ]);

            if ($response->successful()) {
                $devices = $response->json();
                return count($devices) > 0 ? $devices[0] : null;
            }
        } catch (\Exception $e) {
            Log::error("GenieACS: Error fetching device {$serialNumber}: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Reboot device
     */
    public function reboot(string $deviceId)
    {
        return $this->postCommand($deviceId, 'reboot');
    }

    /**
     * Factory Reset
     */
    public function factoryReset(string $deviceId)
    {
        return $this->postCommand($deviceId, 'factoryReset');
    }

    /**
     * Push command to GenieACS
     */
    protected function postCommand(string $deviceId, string $name, array $attributes = [])
    {
        try {
            $response = Http::timeout($this->timeout)
                ->post("{$this->baseUrl}/devices/" . urlencode($deviceId) . "/tasks?connection_request", [
                    'name' => $name,
                ] + $attributes);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("GenieACS: Error sending command {$name} to {$deviceId}: " . $e->getMessage());
        }

        return false;
    }

    /**
     * Set parameter values
     */
    public function setParameterValues(string $deviceId, array $parameters)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->post("{$this->baseUrl}/devices/" . urlencode($deviceId) . "/tasks?connection_request", [
                    'name' => 'setParameterValues',
                    'parameterValues' => $parameters
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("GenieACS: Error setting parameters for {$deviceId}: " . $e->getMessage());
        }

        return false;
    }

    /**
     * Refresh device parameters
     */
    public function refreshDevice(string $deviceId)
    {
        return $this->postCommand($deviceId, 'refreshObject', ['objectName' => 'Device']);
    }
}
