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
        // ... (existing implementation)
        Log::info("Mikrotik: Kicking user {$username} from router {$router->name} ({$router->ip_address})");

        try {
            $client = new \RouterOS\Client([
                'host' => $router->ip_address,
                'user' => $router->username,
                'pass' => $router->password,
                'port' => $router->port ?? 8728,
            ]);

            $request = new \RouterOS\Request('/ppp/active/print');
            $request->setQuery(\RouterOS\Query::where('name', $username));
            $responses = $client->sendSync($request);

            foreach ($responses as $response) {
                if ($response->getType() === \RouterOS\Response::TYPE_DATA) {
                    $id = $response->getArgument('.id');
                    
                    $removeRequest = new \RouterOS\Request('/ppp/active/remove');
                    $removeRequest->setArgument('.id', $id);
                    $client->sendSync($removeRequest);
                    
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
            $client = new \RouterOS\Client([
                'host' => $host,
                'user' => $user,
                'pass' => $pass,
                'port' => $port,
            ]);

            $request = new \RouterOS\Request('/system/resource/print');
            $responses = $client->sendSync($request);

            foreach ($responses as $response) {
                if ($response->getType() === \RouterOS\Response::TYPE_DATA) {
                    return [
                        'board-name' => $response->getArgument('board-name'),
                        'version'    => $response->getArgument('version'),
                        'cpu-load'   => $response->getArgument('cpu-load'),
                        'uptime'     => $response->getArgument('uptime'),
                    ];
                }
            }
        } catch (Exception $e) {
            Log::error("Mikrotik Connection Test Failed: " . $e->getMessage());
        }

        return null;
    }
}
