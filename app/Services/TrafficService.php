<?php

namespace App\Services;

use App\Models\Router;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TrafficService
{
    /**
     * Get real-time traffic for a specific interface.
     * Returns ['rx' => int, 'tx' => int] (bits per second).
     */
    public function getInterfaceTraffic(Router $router, string $interface): array
    {
        try {
            $config = new \RouterOS\Config([
                'host' => $router->ip_address,
                'user' => $router->username,
                'pass' => $router->password,
                'port' => $router->port ?? 8728,
            ]);
            $client = new \RouterOS\Client($config);

            $query = (new \RouterOS\Query('/interface/monitor-traffic'))
                ->equal('interface', $interface)
                ->equal('once');

            $responses = $client->query($query)->read();
            
            Log::info("Mikrotik Traffic Response for {$interface}:", ['response' => $responses]);

            if (!empty($responses) && isset($responses[0])) {
                $response = $responses[0];
                return [
                    'rx' => (int) ($response['rx-bits-per-second'] ?? 0),
                    'tx' => (int) ($response['tx-bits-per-second'] ?? 0),
                ];
            }
        } catch (Exception $e) {
            Log::error("Traffic Monitor Failed: " . $e->getMessage());
        }

        return ['rx' => 0, 'tx' => 0];
    }

    /**
     * Get list of interfaces from router.
     */
    public function getInterfaces(Router $router): array
    {
        try {
            $config = new \RouterOS\Config([
                'host' => $router->ip_address,
                'user' => $router->username,
                'pass' => $router->password,
                'port' => $router->port ?? 8728,
            ]);
            $client = new \RouterOS\Client($config);

            $query = new \RouterOS\Query('/interface/print');
            $responses = $client->query($query)->read();

            Log::info("Mikrotik Interfaces Response:", ['response' => $responses]);

            $interfaces = [];
            foreach ($responses as $response) {
                if (isset($response['name'])) {
                    $interfaces[] = [
                        'name' => $response['name'],
                        'type' => $response['type'] ?? 'unknown'
                    ];
                }
            }
            return $interfaces;
        } catch (Exception $e) {
            Log::error("Get Interfaces Failed: " . $e->getMessage());
            return [];
        }
    }
}
