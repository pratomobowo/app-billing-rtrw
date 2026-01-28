<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Services\PaymentGateways\PaymentGatewayInterface;
use App\Services\PaymentGateways\ManualGateway;
use App\Services\PaymentGateways\MidtransGateway;
use App\Services\PaymentGateways\XenditGateway;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function getGatewayInstance(string $code): PaymentGatewayInterface
    {
        return match ($code) {
            'manual' => new ManualGateway(),
            'midtrans' => new MidtransGateway(),
            'xendit' => new XenditGateway(),
            default => throw new Exception("Gateway $code not supported"),
        };
    }

    public function createPayment(Invoice $invoice, string $gatewayCode): Payment
    {
        $gatewayDb = PaymentGateway::where('code', $gatewayCode)->where('is_active', true)->firstOrFail();
        $gatewayInstance = $this->getGatewayInstance($gatewayCode);

        // 1. Create Transaction at Gateway
        try {
            $response = $gatewayInstance->createTransaction($invoice, $gatewayDb);
        } catch (Exception $e) {
            throw new Exception("Payment Gateway Error: " . $e->getMessage());
        }

        // 2. Save Payment Record
        return Payment::create([
            'invoice_id' => $invoice->id,
            'payment_gateway_id' => $gatewayDb->id,
            'transaction_id' => $response['transaction_id'] ?? null,
            'amount' => $invoice->amount,
            'status' => 'pending',
            'payload' => $response,
        ]);
    }

    public function handleCallback(string $gatewayCode, $request): void
    {
        $gatewayDb = PaymentGateway::where('code', $gatewayCode)->firstOrFail();
        $gatewayInstance = $this->getGatewayInstance($gatewayCode);

        try {
            $result = $gatewayInstance->handleCallback($request, $gatewayDb);
            
            $payment = Payment::where('transaction_id', $result['transaction_id'])
                ->orWhere('id', $result['transaction_id']) // Fallback for manual
                ->first();

            if ($payment && $payment->status !== 'paid') {
                if ($result['status'] === 'paid') {
                    DB::transaction(function () use ($payment, $result) {
                        $payment->update([
                            'status' => 'paid',
                            'paid_at' => now(),
                            'payload' => array_merge($payment->payload ?? [], $result['payload'])
                        ]);
                        
                        // Update Invoice Status
                        $payment->invoice()->update([
                            'status' => 'paid',
                            'paid_at' => now()
                        ]);
                    });
                } else if ($result['status'] === 'failed') {
                    $payment->update([
                        'status' => 'failed',
                        'payload' => array_merge($payment->payload ?? [], $result['payload'])
                    ]);
                }
            }
        } catch (Exception $e) {
            Log::error("Payment Callback Error ($gatewayCode): " . $e->getMessage());
            throw $e;
        }
    }
}
