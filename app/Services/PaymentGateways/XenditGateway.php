<?php

namespace App\Services\PaymentGateways;

use App\Models\Invoice;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class XenditGateway implements PaymentGatewayInterface
{
    public function createTransaction(Invoice $invoice, PaymentGateway $gateway): array
    {
        // Placeholder for Xendit implementation
        return [
            'transaction_id' => 'XND-' . $invoice->id . '-' . time(),
            'payment_url' => 'https://checkout-staging.xendit.co/web/' . $invoice->id,
            'payload' => ['mock' => true],
        ];
    }

    public function handleCallback(Request $request, PaymentGateway $gateway): array
    {
        // Mock callback
        return [
            'transaction_id' => $request->input('external_id'),
            'status' => $request->input('status') === 'PAID' ? 'paid' : 'pending',
            'payload' => $request->all()
        ];
    }
}
