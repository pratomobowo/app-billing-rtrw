<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
use App\Models\Router;
use App\Models\Package;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'router_id' => Router::factory(),
            'package_id' => Package::factory(),
            'name' => fake()->name(),
            'whatsapp' => '08' . fake()->numerify('##########'),
            'address' => fake()->address(),
            'connection_type' => 'pppoe',
            'pppoe_user' => fake()->userName(),
            'pppoe_pass' => '123456',
            'status' => 'active',
            'due_date' => fake()->numberBetween(1, 28),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
        ];
    }
}
