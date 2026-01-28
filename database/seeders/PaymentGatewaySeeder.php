<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    public function run(): void
    {
        PaymentGateway::updateOrCreate(
            ['code' => 'manual'],
            [
                'name' => 'Manual Transfer (BCA/Mandiri)',
                'is_active' => true,
                'config' => [
                    'instruction' => 'Silakan transfer ke BCA 1234567890 a.n PT Vibe',
                ]
            ]
        );

        PaymentGateway::updateOrCreate(
            ['code' => 'midtrans'],
            [
                'name' => 'Midtrans Payment',
                'is_active' => false,
                'config' => [
                    'server_key' => '',
                    'client_key' => '',
                    'is_production' => false
                ]
            ]
        );

        PaymentGateway::updateOrCreate(
            ['code' => 'xendit'],
            [
                'name' => 'Xendit Payment',
                'is_active' => false,
                'config' => [
                    'api_key' => '',
                    'callback_token' => ''
                ]
            ]
        );
    }
}
