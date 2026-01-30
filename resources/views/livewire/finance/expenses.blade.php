<div>
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Manajemen Pengeluaran</h2>
            <p class="text-slate-500 text-sm mt-1">Catat dan pantau semua biaya operasional RT/RW Net Anda.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-2">
            <x-button label="Tambah Pengeluaran" icon="o-plus" class="btn-primary" wire:click="createExpense" />
            <x-button label="Kategori" icon="o-tag" class="btn-ghost" @click="$wire.categoryModal = true" />
        </div>
    </div>

    <!-- Filters Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-input wire:model.live.debounce.300ms="search" placeholder="Cari judul..." icon="o-magnifying-glass" />
        <x-select wire:model.live="filter_category" :options="$categories" placeholder="Semua Kategori" option-value="id" option-label="name" />
        <x-select wire:model.live="filter_month" :options="[
            ['id' => '01', 'name' => 'Januari'], ['id' => '02', 'name' => 'Februari'], ['id' => '03', 'name' => 'Maret'],
            ['id' => '04', 'name' => 'April'], ['id' => '05', 'name' => 'Mei'], ['id' => '06', 'name' => 'Juni'],
            ['id' => '07', 'name' => 'Juli'], ['id' => '08', 'name' => 'Agustus'], ['id' => '09', 'name' => 'September'],
            ['id' => '10', 'name' => 'Oktober'], ['id' => '11', 'name' => 'November'], ['id' => '12', 'name' => 'Desember'],
        ]" placeholder="Pilih Bulan" />
        <x-select wire:model.live="filter_year" :options="[
            ['id' => 2025, 'name' => '2025'], ['id' => 2026, 'name' => '2026'], ['id' => 2027, 'name' => '2027']
        ]" placeholder="Pilih Tahun" />
    </div>

    <!-- Stats Summary Card -->
    <div class="mb-6 bg-gradient-to-br from-primary-600 to-primary-800 rounded-2xl p-6 text-white shadow-lg shadow-primary-900/20">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-primary-100 text-sm font-medium uppercase tracking-wider">Total Pengeluaran Periode Ini</p>
                <h3 class="text-3xl font-bold mt-1">Rp {{ number_format($totalAmount, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-white/20 p-4 rounded-full backdrop-blur-md">
                <span class="material-symbols-outlined text-4xl">payments</span>
            </div>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="text-slate-600 font-bold uppercase text-[10px] tracking-wider py-4 px-6 border-b border-slate-100">Tanggal</th>
                        <th class="text-slate-600 font-bold uppercase text-[10px] tracking-wider py-4 px-6 border-b border-slate-100">Kategori</th>
                        <th class="text-slate-600 font-bold uppercase text-[10px] tracking-wider py-4 px-6 border-b border-slate-100">Judul / Keterangan</th>
                        <th class="text-slate-600 font-bold uppercase text-[10px] tracking-wider py-4 px-6 border-b border-slate-100 text-right">Jumlah</th>
                        <th class="text-slate-600 font-bold uppercase text-[10px] tracking-wider py-4 px-6 border-b border-slate-100 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                        <tr wire:key="{{ $expense->id }}" class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4 text-sm text-slate-500">
                                {{ $expense->expense_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-600 border border-slate-200">
                                    {{ $expense->category->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-800">{{ $expense->title }}</div>
                                @if($expense->description)
                                    <div class="text-xs text-slate-400 mt-0.5">{{ $expense->description }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-red-600">
                                Rp {{ number_format($expense->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <x-button icon="o-pencil" class="btn-ghost btn-sm text-slate-400 hover:text-primary" wire:click="editExpense({{ $expense->id }})" />
                                    <x-button icon="o-trash" class="btn-ghost btn-sm text-slate-400 hover:text-red-500" 
                                        wire:confirm="Hapus pengeluaran ini?" 
                                        wire:click="deleteExpense({{ $expense->id }})" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-20 text-slate-400">
                                <div class="flex flex-col items-center gap-3">
                                    <span class="material-symbols-outlined text-5xl opacity-20">receipt_long</span>
                                    <p class="font-medium">Belum ada data pengeluaran.</p>
                                    <x-button label="Catat Sekarang" class="btn-primary btn-sm" wire:click="createExpense" />
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($expenses->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>

    <!-- Expense Modal -->
    <x-modal wire:model="expenseModal" title="{{ $expense_id ? 'Edit' : 'Tambah' }} Pengeluaran" separator>
        <div class="grid gap-4">
            <x-select label="Kategori" wire:model="expense_category_id" :options="$categories" option-value="id" option-label="name" placeholder="Pilih Kategori" />
            <x-input label="Judul Pengeluaran" wire:model="title" placeholder="Contoh: Pembayaran Indihome Januari" />
            <x-input label="Jumlah (Rp)" wire:model="amount" type="number" prefix="Rp" />
            <x-input label="Tanggal" wire:model="expense_date" type="date" />
            <x-textarea label="Keterangan (Opsional)" wire:model="description" placeholder="Catatan tambahan..." rows="3" />
        </div>
        <x-slot:actions>
            <x-button label="Batal" @click="$wire.expenseModal = false" />
            <x-button label="Simpan" class="btn-primary" wire:click="saveExpense" spinner="saveExpense" />
        </x-slot:actions>
    </x-modal>

    <!-- Category Modal -->
    <x-modal wire:model="categoryModal" title="Tambah Kategori Pengeluaran" separator>
        <div class="grid gap-4">
            <x-input label="Nama Kategori" wire:model="category_name" placeholder="Contoh: Internet, Listrik, Gaji" />
            <x-textarea label="Deskripsi" wire:model="category_description" placeholder="Penjelasan singkat..." rows="2" />
            
            <div class="mt-4">
                <p class="text-xs font-bold text-slate-500 uppercase mb-2">Daftar Kategori Saat Ini:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($categories as $cat)
                        <span class="px-3 py-1 bg-slate-100 rounded-lg text-xs text-slate-700 border border-slate-200">{{ $cat->name }}</span>
                    @endforeach
                </div>
            </div>
        </div>
        <x-slot:actions>
            <x-button label="Tutup" @click="$wire.categoryModal = false" />
            <x-button label="Tambah Kategori" class="btn-primary" wire:click="saveCategory" spinner="saveCategory" />
        </x-slot:actions>
    </x-modal>
</div>
