<?php

namespace App\Services\PaymentGateways;

use App\Models\Invoice;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ManualGateway implements PaymentGatewayInterface
{
    public function createTransaction(Invoice $invoice, PaymentGateway $gateway): array
    {
        // For manual, we just generate a transaction ID and show instructions
        return [
            'transaction_id' => 'MAN-' . strtoupper(Str::random(10)),
            'payment_url' => null, // No URL for manual
            'payload' => [
                'instructions' => $gateway->config['instruction'] ?? 'Silakan transfer ke rekening kami',
            ],
        ];
    }

    public function handleCallback(Request $request, PaymentGateway $gateway): array
    {
        // Manual confirmation usually handled by Admin in UI, 
        // but if we had a dedicated "Confirm Payment" page for user, we'd process it here.
        // For now, this might be used if we upload proof of payment.
        
        return [
            'transaction_id' => $request->input('transaction_id'),
            'status' => 'paid',
            'payload' => $request->all()
        ];
    }
}
