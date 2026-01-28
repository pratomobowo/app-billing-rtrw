<div>
    <!-- Header Area -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Riwayat Invoice</h2>
            <p class="text-sm text-slate-500">Daftar seluruh invoice yang pernah dibuat untuk pelanggan</p>
        </div>
        <div class="flex items-center gap-3">
             <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari invoice atau nama..." 
                    class="pl-10 pr-4 py-2 w-64 rounded-lg bg-white border border-slate-200 text-sm focus:ring-2 focus:ring-primary focus:border-primary text-slate-700 transition-all shadow-sm">
             </div>
             <select wire:model.live="status" class="select select-bordered select-sm h-[38px] bg-white border-slate-200 text-slate-600 rounded-lg">
                <option value="">Semua Status</option>
                <option value="paid">Lunas</option>
                <option value="unpaid">Belum Bayar</option>
                <option value="expired">Expired</option>
             </select>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <x-table :headers="$headers" :rows="$invoices" with-pagination class="text-sm">
            @scope('header_id', $header)
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
                <span class="text-slate-500 italic">{{ $invoice->due_date->format('d M Y') }}</span>
            @endscope

            @scope('cell_paid_at', $invoice)
                <span class="text-slate-500 font-medium">
                    {{ $invoice->paid_at ? $invoice->paid_at->format('d M Y') : '-' }}
                </span>
            @endscope

            @scope('actions', $invoice)
                <div class="flex gap-1 justify-end">
                    <x-button icon="o-eye" class="btn-sm btn-ghost text-slate-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all" tooltip="Detail" />
                    @if($invoice->status == 'unpaid')
                        <x-button icon="o-credit_card" class="btn-sm btn-ghost text-slate-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all" tooltip="Bayar" wire:click="pay({{ $invoice->id }})" />
                    @endif
                    <x-button icon="o-printer" class="btn-sm btn-ghost text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-all" tooltip="Cetak" />
                </div>
            @endscope
        </x-table>
    </div>

    <!-- Payment Modal -->
    <x-modal wire:model="paymentModal" title="Pilih Metode Pembayaran" subtitle="Invoice #{{ $selectedInvoice?->invoice_number }}" separator>
        <div class="grid grid-cols-1 gap-3">
            @foreach($gateways as $gateway)
                <button wire:click="processPayment('{{ $gateway->code }}')" class="flex items-center justify-between p-4 rounded-xl border border-slate-200 hover:border-primary hover:bg-primary/5 transition-all group text-left">
                    <div class="flex items-center gap-3">
                        <div class="bg-white p-2 rounded-lg border border-slate-100 group-hover:border-primary/20">
                            <span class="material-symbols-outlined text-slate-500 group-hover:text-primary">payments</span>
                        </div>
                        <div>
                            <span class="font-bold text-slate-800 block">{{ $gateway->name }}</span>
                            <span class="text-xs text-slate-500">Proses otomatis</span>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-slate-300 group-hover:text-primary">chevron_right</span>
                </button>
            @endforeach
        </div>
        
        <x-slot:actions>
            <x-button label="Batal" @click="$wire.paymentModal = false" />
        </x-slot:actions>
    </x-modal>
</div>
