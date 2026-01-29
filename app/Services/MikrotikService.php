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
            $client = $this->getClient($router);

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
     * Sync PPPoE User to Mikrotik PPP Secret.
     */
    public function syncPppoeUser(Router $router, string $username, string $password, string $profile, string $comment = ''): bool
    {
        try {
            $client = $this->getClient($router);
            
            // Check if user exists
            $query = (new \RouterOS\Query('/ppp/secret/print'))
                ->where('name', $username);
            $responses = $client->query($query)->read();

            $params = [
                'name'     => $username,
                'password' => $password,
                'profile'  => $profile,
                'service'  => 'pppoe',
                'comment'  => $comment,
            ];

            if (empty($responses)) {
                // Create
                $addQuery = new \RouterOS\Query('/ppp/secret/add');
                foreach ($params as $key => $value) {
                    $addQuery->equal($key, $value);
                }
                $client->query($addQuery)->read();
            } else {
                // Update
                $id = $responses[0]['.id'];
                $setQuery = new \RouterOS\Query('/ppp/secret/set');
                $setQuery->equal('.id', $id);
                foreach ($params as $key => $value) {
                    $setQuery->equal($key, $value);
                }
                $client->query($setQuery)->read();
            }
            return true;
        } catch (Exception $e) {
            Log::error("Mikrotik PPPoE Sync Failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete PPPoE User from Mikrotik.
     */
    public function deletePppoeUser(Router $router, string $username): bool
    {
        try {
            $client = $this->getClient($router);
            
            $query = (new \RouterOS\Query('/ppp/secret/print'))
                ->where('name', $username);
            $responses = $client->query($query)->read();

            if (!empty($responses)) {
                $id = $responses[0]['.id'];
                $removeQuery = (new \RouterOS\Query('/ppp/secret/remove'))
                    ->equal('.id', $id);
                $client->query($removeQuery)->read();
            }
            return true;
        } catch (Exception $e) {
            Log::error("Mikrotik PPPoE Delete Failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Sync PPPoE Profile to Mikrotik.
     */
    public function syncPppoeProfile(Router $router, string $name, string $rateLimit): bool
    {
        try {
            $client = $this->getClient($router);
            
            // Check if profile exists
            $query = (new \RouterOS\Query('/ppp/profile/print'))
                ->where('name', $name);
            $responses = $client->query($query)->read();

            $params = [
                'name'       => $name,
                'rate-limit' => $rateLimit,
            ];

            if (empty($responses)) {
                // Create
                $addQuery = new \RouterOS\Query('/ppp/profile/add');
                foreach ($params as $key => $value) {
                    $addQuery->equal($key, $value);
                }
                $client->query($addQuery)->read();
            } else {
                // Update
                $id = $responses[0]['.id'];
                $setQuery = new \RouterOS\Query('/ppp/profile/set');
                $setQuery->equal('.id', $id);
                foreach ($params as $key => $value) {
                    $setQuery->equal($key, $value);
                }
                $client->query($setQuery)->read();
            }
            return true;
        } catch (Exception $e) {
            Log::error("Mikrotik PPPoE Profile Sync Failed: " . $e->getMessage());
            return false;
        }
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

    /**
     * Helper to get Mikrotik Client.
     */
    protected function getClient(Router $router): \RouterOS\Client
    {
        $config = new \RouterOS\Config([
            'host' => $router->ip_address,
            'user' => $router->username,
            'pass' => $router->password,
            'port' => $router->port ?? 8728,
        ]);
        return new \RouterOS\Client($config);
    }
}
