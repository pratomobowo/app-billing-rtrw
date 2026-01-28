<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        \App\Models\User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Default Settings
        \App\Models\Setting::create(['key' => 'company_name', 'value' => 'Vibe Billing']);
        \App\Models\Setting::create(['key' => 'company_address', 'value' => 'Jl. Merdeka No. 1, Jakarta']);

        // Dummy OLT
        $olt = \App\Models\Olt::create([
            'name' => 'ZTE-C320-Pusat',
            'ip_address' => '10.10.10.1',
            'type' => 'ZTE',
            'username' => 'admin',
            'password' => 'admin'
        ]);

        // Dummy ONU
        \App\Models\Onu::create([
            'olt_id' => $olt->id,
            'name' => 'Budi Santoso',
            'serial_number' => 'ZTEGC829101',
            'interface' => 'PON 1/1/1',
            'signal' => -18.4
        ]);

        // Dummy ODP
        \App\Models\Odp::create([
            'name' => 'ODP-JKT-001',
            'latitude' => -6.200000,
            'longitude' => 106.816666,
            'capacity' => 16,
            'filled' => 5
        ]);

        // Create 2 Routers
        $routers = \App\Models\Router::factory(2)->create();

        // Create 5 Packages
        $packages = \App\Models\Package::factory(5)->create();

        // Create 20 Customers with Invoices
        foreach ($routers as $router) {
            \App\Models\Customer::factory(10)->create([
                'router_id' => $router->id,
                'package_id' => $packages->random()->id,
            ])->each(function ($customer) {
                \App\Models\Invoice::factory()->create([
                    'customer_id' => $customer->id,
                    'amount' => $customer->package->price,
                ]);
            });
        }

        $this->call([
             PaymentGatewaySeeder::class,
        ]);
    }
}
