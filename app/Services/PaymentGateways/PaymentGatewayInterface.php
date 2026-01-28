<?php

namespace App\Services\PaymentGateways;

use App\Models\Invoice;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    /**
     * Create a transaction for the given invoice.
     * Returns an array with 'transaction_id', 'payment_url' (optional), 'payload'.
     */
    public function createTransaction(Invoice $invoice, PaymentGateway $gateway): array;

    /**
     * Handle webhook/callback request.
     * Returns array with 'transaction_id', 'status' (paid/failed), 'payload'.
     */
    public function handleCallback(Request $request, PaymentGateway $gateway): array;
}
