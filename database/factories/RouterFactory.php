<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Router>
 */
class RouterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'MikroTik Core ' . fake()->city(),
            'ip_address' => fake()->ipv4(),
            'port' => 8728,
            'username' => 'admin',
            'password' => 'password',
            'status' => 'online',
        ];
    }
}
