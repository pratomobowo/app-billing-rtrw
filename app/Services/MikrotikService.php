<?php

namespace App\Services;

use Exception;
use EvilFreelancer\RouterOs\Client;
use Illuminate\Support\Facades\Log;

class MikrotikService
{
    protected ?Client $client = null;

    /**
     * Connect to the MikroTik router.
     */
    public function connect(string $ip, string $user, string $password, int $port = 8728): bool
    {
        try {
            $this->client = new Client([
                'host' => $ip,
                'user' => $user,
                'pass' => $password,
                'port' => $port,
                'timeout' => 5, // 5 seconds timeout
            ]);
            return true;
        } catch (Exception $e) {
            Log::error("MikroTik Connection Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get system resources (CPU, Uptime, etc.)
     */
    public function getSystemResources(): ?array
    {
        if (!$this->client) {
            return null;
        }

        try {
            $request = $this->client->query('/system/resource/print');
            $response = $this->client->read($request);
            return $response[0] ?? null;
        } catch (Exception $e) {
            Log::error("MikroTik Resource Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get list of interfaces.
     */
    public function getInterfaces(): array
    {
        if (!$this->client) {
            return [];
        }

        try {
            $request = $this->client->query('/interface/print');
            return $this->client->read($request);
        } catch (Exception $e) {
            Log::error("MikroTik Interface Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Test connection and return resource data if successful.
     */
    public function testConnection(string $ip, string $user, string $password, int $port = 8728): ?array
    {
        if ($this->connect($ip, $user, $password, $port)) {
            return $this->getSystemResources();
        }
        return null;
    }

    /**
     * Add a PPP Secret (User).
     */
    public function addPppSecret(string $user, string $password, string $service = 'pppoe', string $profile = 'default'): bool
    {
        if (!$this->client) {
            return false;
        }

        try {
            $query = (new \EvilFreelancer\RouterOs\Query('/ppp/secret/add'))
                ->equal('name', $user)
                ->equal('password', $password)
                ->equal('service', $service)
                ->equal('profile', $profile);
            
            $this->client->query($query)->read();
            return true;
        } catch (Exception $e) {
            Log::error("MikroTik Add Secret Error: " . $e->getMessage());
            return false;
        }
    }
}
