<div>
    <!-- Header Area -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Paket Internet</h2>
            <p class="text-sm text-slate-500">Kelola paket layanan internet dan limitasi bandwidth</p>
        </div>
        <div class="flex flex-col sm:flex-row w-full sm:w-auto gap-3">
             <div class="relative w-full sm:w-auto">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari paket..." 
                    class="pl-10 pr-4 py-2 w-full sm:w-64 rounded-lg bg-white border border-slate-200 text-sm focus:ring-2 focus:ring-primary focus:border-primary text-slate-700 transition-all shadow-sm">
             </div>
             <x-button label="Tambah Paket" icon="o-plus" class="btn-primary shadow-lg shadow-primary/20 w-full sm:w-auto" wire:click="create" />
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <x-table :headers="$headers" :rows="$packages" with-pagination class="text-sm min-w-[700px] sm:min-w-full">
            @scope('header_id', $header)
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $header['label'] }}</span>
            @endscope

            @scope('header_name', $header)
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $header['label'] }}</span>
            @endscope

            @scope('header_bandwidth_limit', $header)
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $header['label'] }}</span>
            @endscope

            @scope('header_price', $header)
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $header['label'] }}</span>
            @endscope

            @scope('header_description', $header)
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $header['label'] }}</span>
            @endscope

            @scope('cell_name', $package)
                <div class="flex items-center gap-3 py-1">
                    <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[20px]">wifi</span>
                    </div>
                    <span class="font-semibold text-slate-900">{{ $package->name }}</span>
                </div>
            @endscope

            @scope('cell_price', $package)
                <span class="font-medium text-slate-700">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
            @endscope

            @scope('cell_bandwidth_limit', $package)
                <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                    <span class="material-symbols-outlined text-[14px] mr-1">speed</span>
                    {{ $package->bandwidth_limit }}
                </div>
            @endscope

            @scope('actions', $package)
                <div class="flex gap-1 justify-end">
                    <x-button icon="o-pencil" class="btn-sm btn-ghost text-slate-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all" wire:click="edit({{ $package->id }})" />
                    <x-button icon="o-trash" class="btn-sm btn-ghost text-slate-400 hover:text-error hover:bg-error/5 rounded-lg transition-all" wire:click="delete({{ $package->id }})" 
                        wire:confirm="Apakah Anda yakin ingin menghapus paket ini?" />
                </div>
            @endscope
        </x-table>
    </div>

    <!-- Modal for Create/Edit -->
    <x-modal wire:model="packageModal" title="{{ $editingPackage ? 'Edit Paket' : 'Tambah Paket Baru' }}" subtitle="Lengkapi detail paket di bawah ini" separator>
        <div class="grid gap-5">
            <x-input label="Nama Paket" wire:model="name" placeholder="Contoh: Paket 10 Mbps" icon="o-wifi" />
            <x-input label="Limit Bandwidth" wire:model="bandwidth_limit" placeholder="Contoh: 10M/10M" icon="o-bolt" />
            <x-input label="Harga Bulanan" wire:model="price" type="number" prefix="Rp" placeholder="0" />
            <x-textarea label="Keterangan" wire:model="description" placeholder="Deskripsi paket (opsional)" />
        </div>

        <x-slot:actions>
            <x-button label="Batal" @click="$wire.packageModal = false" />
            <x-button label="Simpan Paket" class="btn-primary" wire:click="save" spinner="save" />
        </x-slot:actions>
    </x-modal>
</div>
