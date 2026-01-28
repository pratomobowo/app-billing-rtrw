<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $mbps = fake()->randomElement([5, 10, 20, 50, 100]);
        return [
            'name' => "Paket {$mbps} Mbps",
            'price' => $mbps * 10000,
            'bandwidth_limit' => "{$mbps}M/{$mbps}M",
            'description' => "Paket internet super cepat {$mbps} Mbps",
        ];
    }
}
