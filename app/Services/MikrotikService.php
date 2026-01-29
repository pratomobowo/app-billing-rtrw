<?php

namespace App\Services;

use App\Models\Router;
use Exception;
use Illuminate\Support\Facades\Log;

class MikrotikService
{
    /**
     * Kick a user from the router (force PPPoE session disconnect).
     */
    public function kickUser(Router $router, string $username): bool
    {
        Log::info("Mikrotik: Kicking user {$username} from router {$router->name} ({$router->ip_address})");

        try {
            $config = new \RouterOS\Config([
                'host' => $router->ip_address,
                'user' => $router->username,
                'pass' => $router->password,
                'port' => $router->port ?? 8728,
            ]);
            $client = new \RouterOS\Client($config);

            $query = (new \RouterOS\Query('/ppp/active/print'))
                ->where('name', $username);
            
            $responses = $client->query($query)->read();

            foreach ($responses as $response) {
                if (isset($response['.id'])) {
                    $id = $response['.id'];
                    
                    $removeQuery = (new \RouterOS\Query('/ppp/active/remove'))
                        ->equal('.id', $id);
                    
                    $client->query($removeQuery);
                    
                    return true;
                }
            }
        } catch (Exception $e) {
            Log::error("Mikrotik Kick Failed: " . $e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Test connection to Mikrotik router and get basic system info.
     */
    public function testConnection(string $host, string $user, string $pass, int $port = 8728): ?array
    {
        try {
            $config = new \RouterOS\Config([
                'host' => $host,
                'user' => $user,
                'pass' => $pass,
                'port' => $port,
            ]);
            $client = new \RouterOS\Client($config);

            $query = new \RouterOS\Query('/system/resource/print');
            $responses = $client->query($query)->read();

            if (!empty($responses) && isset($responses[0])) {
                $response = $responses[0];
                return [
                    'board-name' => $response['board-name'] ?? '-',
                    'version'    => $response['version'] ?? '-',
                    'cpu-load'   => $response['cpu-load'] ?? 0,
                    'uptime'     => $response['uptime'] ?? '-',
                ];
            }
        } catch (Exception $e) {
            Log::error("Mikrotik Connection Test Failed: " . $e->getMessage());
        }

        return null;
    }
}
