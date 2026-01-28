<div>
    <!-- Header Area -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Tagihan & Penagihan</h2>
            <p class="text-sm text-slate-500">Pantau status pembayaran dan kelola invoice pelanggan</p>
        </div>
        <div class="flex flex-wrap gap-3">
             <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari invoice atau nama..." 
                    class="pl-10 pr-4 py-2 w-64 rounded-lg bg-white border border-slate-200 text-sm focus:ring-2 focus:ring-primary focus:border-primary text-slate-700 transition-all shadow-sm">
             </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <x-table :headers="$headers" :rows="$invoices" with-pagination class="text-sm">
            @scope('header_id', $header)
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $header['label'] }}</span>
            @endscope

            @scope('header_invoice_number', $header)
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $header['label'] }}</span>
            @endscope

            @scope('header_customer.name', $header)
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $header['label'] }}</span>
            @endscope

            @scope('header_amount', $header)
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $header['label'] }}</span>
            @endscope

            @scope('header_status', $header)
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $header['label'] }}</span>
            @endscope

            @scope('header_due_date', $header)
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $header['label'] }}</span>
            @endscope

            @scope('cell_invoice_number', $invoice)
                <span class="font-mono text-primary font-bold">#{{ $invoice->invoice_number }}</span>
            @endscope

            @scope('cell_amount', $invoice)
                <span class="font-semibold text-slate-900">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
            @endscope

            @scope('cell_status', $invoice)
                @if($invoice->status === 'paid')
                    <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                        <span class="size-1.5 rounded-full bg-green-500 mr-2"></span>
                        Lunas
                    </div>
                @elseif($invoice->status === 'unpaid')
                    <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                        <span class="size-1.5 rounded-full bg-amber-500 animate-pulse mr-2"></span>
                        Pending
                    </div>
                @else
                    <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                        <span class="size-1.5 rounded-full bg-red-500 mr-2"></span>
                        Expired
                    </div>
                @endif
            @endscope

            @scope('cell_due_date', $invoice)
                <div class="flex items-center text-slate-500 italic">
                    <span class="material-symbols-outlined text-[16px] mr-1">event</span>
                    {{ $invoice->due_date->format('d M Y') }}
                </div>
            @endscope

            @scope('actions', $invoice)
                <div class="flex gap-1 justify-end">
                    @if($invoice->status !== 'paid')
                        <x-button icon="o-check-circle" class="btn-sm btn-ghost text-slate-400 hover:text-success hover:bg-success/5 rounded-lg transition-all" 
                            tooltip="Konfirmasi Bayar" wire:click="confirmPayment({{ $invoice->id }})" />
                        <x-button icon="o-paper-airplane" class="btn-sm btn-ghost text-slate-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all" 
                            tooltip="Kirim Pengingat WA" wire:click="sendReminder({{ $invoice->id }})" />
                    @endif
                    <x-button icon="o-printer" class="btn-sm btn-ghost text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-all" 
                        tooltip="Cetak Invoice" />
                </div>
            @endscope
        </x-table>
    </div>

    <!-- Payment Confirmation Modal -->
    <x-modal wire:model="paymentModal" title="Konfirmasi Pembayaran" subtitle="Pastikan dana sudah diterima sebelum konfirmasi" separator>
        @if($selectedInvoice)
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 mb-5">
                <div class="flex justify-between mb-2">
                    <span class="text-slate-500 text-sm">Invoice</span>
                    <span class="font-mono font-bold text-slate-800">#{{ $selectedInvoice->invoice_number }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-slate-500 text-sm">Pelanggan</span>
                    <span class="font-semibold text-slate-800">{{ $selectedInvoice->customer->name }}</span>
                </div>
                <div class="flex justify-between border-t border-slate-200 mt-2 pt-2">
                    <span class="text-slate-500 font-bold text-sm uppercase tracking-wider">Total Bayar</span>
                    <span class="text-lg font-bold text-primary">Rp {{ number_format($selectedInvoice->amount, 0, ',', '.') }}</span>
                </div>
            </div>
            <p class="text-sm text-slate-600 text-center italic">Dengan menekan tombol konfirmasi, status invoice akan berubah menjadi **Lunas**.</p>
        @endif

        <x-slot:actions>
            <x-button label="Batal" @click="$wire.paymentModal = false" />
            <x-button label="Ya, Konfirmasi Bayar" class="btn-success text-white" wire:click="markAsPaid" spinner="markAsPaid" />
        </x-slot:actions>
    </x-modal>
</div>
