<div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Pelanggan</h2>
            <p class="text-sm text-slate-500">Kelola data pelanggan, paket internet, dan status isolasi</p>
        </div>
        <div class="flex flex-col sm:flex-row w-full sm:w-auto gap-3">
             <div class="relative w-full sm:w-auto">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari pelanggan..." 
                    class="pl-10 pr-4 py-2 w-full sm:w-64 rounded-lg bg-white border border-slate-200 text-sm focus:ring-2 focus:ring-primary focus:border-primary text-slate-700 transition-all shadow-sm">
             </div>
             <x-button label="Tambah Pelanggan" icon="o-plus" class="btn-primary shadow-lg shadow-primary/20 w-full sm:w-auto" wire:click="create" />
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <x-table :headers="$headers" :rows="$customers" with-pagination class="text-sm min-w-[800px] sm:min-w-full">
            @scope('header_id', $header)
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $header['label'] }}</span>
            @endscope

            @scope('header_name', $header)
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $header['label'] }}</span>
            @endscope

            @scope('header_whatsapp', $header)
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $header['label'] }}</span>
            @endscope

            @scope('header_package.name', $header)
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $header['label'] }}</span>
            @endscope

            @scope('header_router.name', $header)
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $header['label'] }}</span>
            @endscope

            @scope('header_status', $header)
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $header['label'] }}</span>
            @endscope

            @scope('cell_name', $customer)
                <div class="flex items-center gap-3 py-1">
                    @php
                        $colors = ['bg-blue-500', 'bg-purple-500', 'bg-emerald-500', 'bg-orange-500', 'bg-pink-500'];
                        $color = $colors[ord(substr($customer->name, 0, 1)) % count($colors)];
                    @endphp
                    <div class="size-9 rounded-full {{ $color }} flex items-center justify-center text-white font-bold text-sm shadow-sm">
                        {{ substr($customer->name, 0, 1) }}
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-slate-900">{{ $customer->name }}</span>
                        <span class="text-[11px] text-slate-500">{{ $customer->connection_type }}</span>
                    </div>
                </div>
            @endscope

            @scope('cell_whatsapp', $customer)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customer->whatsapp) }}" target="_blank" class="flex items-center gap-1.5 text-slate-600 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[18px]">chat</span>
                    <span>{{ $customer->whatsapp }}</span>
                </a>
            @endscope

            @scope('cell_package.name', $customer)
                <div class="flex flex-col">
                    <span class="font-medium text-slate-700">{{ $customer->package->name }}</span>
                    <span class="text-[11px] text-slate-500">Rp {{ number_format($customer->package->price, 0, ',', '.') }}</span>
                </div>
            @endscope

            @scope('cell_status', $customer)
                @if($customer->status == 'active')
                    <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                        <div class="h-1.5 w-1.5 rounded-full bg-green-500 mr-1.5"></div>
                        Aktif
                    </div>
                @else
                    <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                        <div class="h-1.5 w-1.5 rounded-full bg-red-500 mr-1.5"></div>
                        Terisolir
                    </div>
                @endif
            @endscope

            @scope('actions', $customer)
                <div class="flex gap-1 justify-end">
                    <x-button icon="o-pencil" class="btn-sm btn-ghost text-slate-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all" wire:click="edit({{ $customer->id }})" />
                    <x-button icon="o-trash" class="btn-sm btn-ghost text-slate-400 hover:text-error hover:bg-error/5 rounded-lg transition-all" wire:click="delete({{ $customer->id }})" wire:confirm="Hapus pelanggan & data Radius?" />
                </div>
            @endscope
        </x-table>
    </div>

    <!-- Customer Modal -->
    <x-modal wire:model="customerModal" title="{{ $editingCustomer ? 'Edit Pelanggan' : 'Tambah Pelanggan Baru' }}" subtitle="Informasi pelanggan & koneksi" separator box-class="max-w-2xl">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Personal Info -->
            <div class="col-span-1 md:col-span-2">
                <h3 class="font-bold text-slate-700 mb-2 border-b pb-1">Data Diri</h3>
            </div>
            <x-input label="Nama Lengkap" wire:model.live="name" placeholder="John Doe" />
            <x-input label="No. WhatsApp" wire:model="whatsapp" placeholder="08123456789" hint="Wajib aktif untuk notifikasi" />
            <div class="col-span-1 md:col-span-2">
                <x-textarea label="Alamat Pemasangan" wire:model="address" placeholder="Jalan Mawar No. 12..." rows="2" />
            </div>

            <!-- Connection Info -->
            <div class="col-span-1 md:col-span-2 mt-2">
                <h3 class="font-bold text-slate-700 mb-2 border-b pb-1">Koneksi & Layanan</h3>
            </div>
            
            <x-select label="Pilih Router" wire:model="router_id" :options="$routers" option-label="name" option-value="id" placeholder="-- Pilih Router --" />
            <x-select label="Pilih Paket" wire:model="package_id" :options="$packages" option-label="name" option-value="id" placeholder="-- Pilih Paket --" />
            
            <x-select label="Status" wire:model="status" :options="[
                ['id' => 'active', 'name' => 'Active'],
                ['id' => 'isolated', 'name' => 'Isolated'],
            ]" option-label="name" option-value="id" />
            <x-select label="Tipe Koneksi" wire:model="connection_type" :options="[['id'=>'pppoe','name'=>'PPPoE'],['id'=>'hotspot','name'=>'Hotspot (Voucher)'],['id'=>'static','name'=>'Static IP']]" option-label="name" option-value="id" />

            <x-input label="Jatuh Tempo (Tgl)" wire:model="due_date" type="number" min="1" max="31" suffix="Setiap Bulan" />
            <!-- Physical Connection (ODP) -->
            <div class="col-span-1 md:col-span-2 mt-2">
                <h3 class="font-bold text-slate-700 mb-2 border-b pb-1">Lokasi & ODP</h3>
            </div>
            <x-select label="Pilih ODP" wire:model="odp_id" :options="$odps" option-label="name" option-value="id" placeholder="-- Pilih ODP --" icon="o-share" />
            <x-input label="Port ODP" wire:model="odp_port" type="number" placeholder="Nomor Port (1-16)" icon="o-device-phone-mobile" />

            <div class="grid grid-cols-2 gap-4 col-span-1 md:col-span-2">
                <x-input label="Latitude" wire:model="latitude" placeholder="-6.200000" />
                <x-input label="Longitude" wire:model="longitude" placeholder="106.816666" />
            </div>

            <!-- Technician/Credentials -->
            @if($connection_type === 'pppoe')
                <div class="col-span-1 md:col-span-2 bg-blue-50 p-3 rounded-lg border border-blue-100 mt-2">
                    <span class="text-xs font-bold text-blue-800 uppercase block mb-2">Kredensial PPPoE (Disinkronkan ke Radius/Mikrotik)</span>
                    <div class="grid grid-cols-2 gap-3">
                        <x-input label="Username PPPoE" wire:model="pppoe_user" icon="o-user" />
                        <x-input label="Password PPPoE" wire:model="pppoe_pass" icon="o-key" type="password" />
                    </div>
                </div>
            @endif


        </div>
 
        <x-slot:actions>
            <x-button label="Batal" @click="$wire.customerModal = false" />
            <x-button label="Simpan Pelanggan" class="btn-primary" wire:click="save" spinner="save" />
        </x-slot:actions>
    </x-modal>
</div>
