<?php

namespace App\Services;

use App\Models\Router;
use Exception;
use Illuminate\Support\Facades\Log;

class TrafficService
{
    /**
     * Get real-time traffic for a specific interface.
     * Returns ['rx' => int, 'tx' => int] (bits per second).
     */
    public function getInterfaceTraffic(Router $router, string $interface): array
    {
        // In a real scenario, use RouterOS-API to connect.
        // For development/demo without real router, return mock data.
        
        // Mock Data Simulation
        // Simulating random traffic between 1Mbps and 50Mbps
        return [
            'rx' => rand(1000000, 50000000), // Random 1-50 Mbps
            'tx' => rand(500000, 20000000),  // Random 0.5-20 Mbps
        ];

        /* Real Implementation Logic:
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
            
            if (!empty($responses) && isset($responses[0])) {
                $response = $responses[0];
                return [
                    'rx' => (int) ($response['rx-bits-per-second'] ?? 0),
                    'tx' => (int) ($response['tx-bits-per-second'] ?? 0),
                ];
            }
        } catch (Exception $e) {
            Log::error("Traffic Monitor Failed: " . $e->getMessage());
            return ['rx' => 0, 'tx' => 0];
        }
        */
    }

    /**
     * Get list of interfaces from router.
     */
    public function getInterfaces(Router $router): array
    {
        // Mock Interfaces
        return [
            ['name' => 'ether1-gateway', 'type' => 'ether'],
            ['name' => 'ether2-lan', 'type' => 'ether'],
            ['name' => 'wlan1', 'type' => 'wlan'],
            ['name' => 'pppoe-out1', 'type' => 'pppoe-out'],
        ];
    }
}
