<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
use App\Models\Customer;

class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        $month = now()->month;
        $year = now()->year;
        return [
            'customer_id' => Customer::factory(),
            'invoice_number' => 'INV/' . $year . '/' . $month . '/' . fake()->unique()->numerify('####'),
            'month' => $month,
            'year' => $year,
            'amount' => 150000,
            'status' => fake()->randomElement(['unpaid', 'paid']),
            'due_date' => now()->addDays(5),
            'paid_at' => null,
        ];
    }
}
