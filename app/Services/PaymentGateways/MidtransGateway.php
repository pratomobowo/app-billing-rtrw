<?php

namespace App\Services\PaymentGateways;

use App\Models\Invoice;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class MidtransGateway implements PaymentGatewayInterface
{
    public function createTransaction(Invoice $invoice, PaymentGateway $gateway): array
    {
        // Placeholder for Midtrans implementation
        // Would normally use Midtrans SDK or HTTP Request here
        
        $serverKey = $gateway->config['server_key'] ?? '';
        
        // Mock response
        return [
            'transaction_id' => 'MID-' . $invoice->id . '-' . time(),
            'payment_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $invoice->id,
            'payload' => ['mock' => true],
        ];
    }

    public function handleCallback(Request $request, PaymentGateway $gateway): array
    {
        // Mock callback handler
        $status = $request->input('transaction_status');
        $transId = $request->input('order_id');
        
        return [
            'transaction_id' => $transId,
            'status' => ($status == 'settlement' || $status == 'capture') ? 'paid' : 'failed',
            'payload' => $request->all()
        ];
    }
}
