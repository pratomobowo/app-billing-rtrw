<?php

namespace App\Livewire\Billing;

use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use WithPagination, Toast;

    public string $search = '';
    public string $status = '';
    
    public bool $paymentModal = false;
    public ?Invoice $selectedInvoice = null;

    public array $headers = [
        ['key' => 'id', 'label' => '#'],
        ['key' => 'invoice_number', 'label' => 'No. Invoice'],
        ['key' => 'customer.name', 'label' => 'Pelanggan'],
        ['key' => 'amount', 'label' => 'Tagihan'],
        ['key' => 'status', 'label' => 'Status'],
        ['key' => 'due_date', 'label' => 'Jatuh Tempo'],
    ];

    public function confirmPayment(Invoice $invoice): void
    {
        $this->selectedInvoice = $invoice;
        $this->paymentModal = true;
    }

    public function markAsPaid(): void
    {
        if ($this->selectedInvoice) {
            $this->selectedInvoice->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
            $this->success('Pembayaran berhasil dikonfirmasi.');
            $this->paymentModal = false;
        }
    }

    public function sendReminder(Invoice $invoice): void
    {
        // Placeholder for GOWA v8 integration
        $this->info('WhatsApp reminder dikirim ke ' . $invoice->customer->whatsapp);
    }

    public function render()
    {
        $invoices = Invoice::with('customer')
            ->where('status', '!=', 'paid')
            ->when($this->search, function ($q) {
                $q->where('invoice_number', 'like', "%{$this->search}%")
                  ->orWhereHas('customer', fn($c) => $c->where('name', 'like', "%{$this->search}%"));
            })
            ->orderBy('due_date', 'asc')
            ->paginate(10);

        return view('livewire.billing.index', [
            'invoices' => $invoices
        ]);
    }
}
