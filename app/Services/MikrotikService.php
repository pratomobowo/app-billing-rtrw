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
        // In a real scenario, use RouterOS-API to disconnect.
        // For development/demo without real router, we log it.
        
        Log::info("Mikrotik: Kicking user {$username} from router {$router->name} ({$router->ip_address})");

        /* Real Implementation Logic:
        try {
            $client = new \RouterOS\Client([
                'host' => $router->ip_address,
                'user' => $router->username,
                'pass' => $router->password,
                'port' => $router->port ?? 8728,
            ]);

            // Find PPPoE active session
            $request = new \RouterOS\Request('/ppp/active/print');
            $request->setQuery(\RouterOS\Query::where('name', $username));
            $responses = $client->sendSync($request);

            foreach ($responses as $response) {
                if ($response->getType() === \RouterOS\Response::TYPE_DATA) {
                    $id = $response->getArgument('.id');
                    
                    // Remove the session
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
        */

        return true;
    }
}
