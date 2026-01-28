<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, \Mary\Traits\Toast;

    public string $search = '';
    public string $status = '';

    public array $headers = [
        ['key' => 'id', 'label' => '#'],
        ['key' => 'invoice_number', 'label' => 'No. Invoice'],
        ['key' => 'customer.name', 'label' => 'Pelanggan'],
        ['key' => 'amount', 'label' => 'Tagihan'],
        ['key' => 'status', 'label' => 'Status'],
        ['key' => 'due_date', 'label' => 'Jatuh Tempo'],
        ['key' => 'paid_at', 'label' => 'Tgl Bayar'],
    ];

    public bool $paymentModal = false;
    public ?Invoice $selectedInvoice = null;
    public $gateways = [];

    public function mount()
    {
        $this->gateways = \App\Models\PaymentGateway::where('is_active', true)->get();
    }

    public function pay(Invoice $invoice)
    {
        $this->selectedInvoice = $invoice;
        $this->paymentModal = true;
    }

    public function processPayment(\App\Services\PaymentService $service, string $gatewayCode)
    {
        try {
            $payment = $service->createPayment($this->selectedInvoice, $gatewayCode);
            
            $this->paymentModal = false;

            if ($payment->payment_gateway_id == 1) { // Manual - assumption id 1 is manual or check code
                 // For manual, we might want to show another modal with instructions
                 // For now, toast success
                 $this->success('Silakan ikuti instruksi pembayaran manual.', position: 'toast-bottom');
            } else {
                 // Redirect to payment URL
                 if (isset($payment->payload['payment_url'])) {
                     return redirect()->away($payment->payload['payment_url']);
                 }
                 $this->success('Transaksi berhasil dibuat.');
            }

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function render()
    {
        $invoices = Invoice::with('customer')
            ->when($this->search, function ($q) {
                $q->where('invoice_number', 'like', "%{$this->search}%")
                  ->orWhereHas('customer', fn($c) => $c->where('name', 'like', "%{$this->search}%"));
            })
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.invoices.index', [
            'invoices' => $invoices
        ]);
    }
}
