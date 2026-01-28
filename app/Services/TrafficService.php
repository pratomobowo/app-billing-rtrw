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
            $client = new \RouterOS\Client([
                'host' => $router->ip_address,
                'user' => $router->username,
                'pass' => $router->password,
                'port' => $router->port ?? 8728,
            ]);

            $request = new \RouterOS\Request('/interface/monitor-traffic');
            $request->setArgument('interface', $interface);
            $request->setArgument('once', '');

            $responses = $client->sendSync($request);
            
            foreach ($responses as $response) {
                if ($response->getType() === \RouterOS\Response::TYPE_DATA) {
                    return [
                        'rx' => (int) $response->getArgument('rx-bits-per-second'),
                        'tx' => (int) $response->getArgument('tx-bits-per-second'),
                    ];
                }
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
