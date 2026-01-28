<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Manajemen OLT</h2>
            <p class="text-sm text-slate-500">Daftar perangkat Optical Line Terminal</p>
        </div>
        <x-button label="Tambah OLT" icon="o-plus" class="btn-primary" wire:click="create" />
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <x-table :headers="$headers" :rows="$olts" striped>
            @scope('actions', $olt)
                <div class="flex gap-2">
                    <x-button icon="o-eye" class="btn-sm btn-ghost text-primary" wire:click="showDetail({{ $olt->id }})" />
                    <x-button icon="o-pencil" class="btn-sm btn-ghost text-slate-500" wire:click="edit({{ $olt->id }})" />
                </div>
            @endscope
        </x-table>
    </div>

    <!-- Edit/Create Modal -->
    <x-modal wire:model="oltModal" title="{{ $editingOlt ? 'Edit OLT' : 'Tambah OLT' }}">
        <div class="grid grid-cols-1 gap-4">
            <x-input label="Nama OLT" wire:model="name" placeholder="Contoh: OLT Pusat" />
            <div class="grid grid-cols-2 gap-4">
                <x-input label="IP Address" wire:model="ip_address" placeholder="192.168.1.100" />
                <x-input label="Port (Telnet)" wire:model="port" type="number" />
            </div>
            <x-select label="Tipe Merk" wire:model="type" :options="[['id'=>'ZTE','name'=>'ZTE C320/C300'],['id'=>'HUAWEI','name'=>'Huawei MA5608T'],['id'=>'EPON','name'=>'Generic EPON']]" option-label="name" option-value="id" />
            
            <div class="grid grid-cols-2 gap-4">
                <x-input label="Username" wire:model="username" />
                <x-input label="Password" wire:model="password" type="password" />
            </div>
        </div>
        <x-slot:actions>
            <x-button label="Batal" @click="$wire.oltModal = false" />
            <x-button label="Simpan" class="btn-primary" type="submit" wire:click="save" />
        </x-slot:actions>
    </x-modal>

    <!-- Detail Modal -->
    <x-modal wire:model="detailModal" title="Daftar ONU" class="backdrop-blur">
        <div class="mb-4 flex justify-between items-center">
             <div>
                <h3 class="font-bold text-lg">{{ $selectedOlt?->name }}</h3>
                <p class="text-xs text-slate-500">{{ $selectedOlt?->ip_address }}</p>
             </div>
             <x-button icon="o-arrow-path" label="Refresh Semua" class="btn-sm btn-ghost text-primary" wire:click="refreshAll" spinner />
        </div>
        
        <div class="overflow-x-auto bg-slate-50 rounded-lg p-1">
            <table class="table w-full">
                <thead>
                    <tr class="text-slate-500 text-xs uppercase">
                        <th>Nama</th>
                        <th>SN</th>
                        <th>Sinyal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if($selectedOlt)
                        @forelse($selectedOlt->onus as $onu)
                            <tr class="hover:bg-white transition-colors">
                                <td class="font-bold text-slate-700 text-sm">{{ $onu->name }}</td>
                                <td class="font-mono text-[10px]">{{ $onu->serial_number }}</td>
                                <td>
                                    @if($onu->signal)
                                        @php
                                            $color = $onu->signal >= -24 ? 'text-green-600 bg-green-100' : 
                                                    ($onu->signal >= -27 ? 'text-yellow-600 bg-yellow-100' : 'text-red-600 bg-red-100');
                                        @endphp
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold font-mono {{ $color }}">
                                            {{ $onu->signal }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-[10px] italic">-</span>
                                    @endif
                                </td>
                                <td>
                                    <x-button icon="o-arrow-path" size="sm" class="btn-xs btn-ghost" wire:click="refreshSignal({{ $onu->id }})" spinner />
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-xs p-4">Tidak ada ONU</td></tr>
                        @endforelse
                    @endif
                </tbody>
            </table>
        </div>
        
        <x-slot:actions>
            <x-button label="Tutup" @click="$wire.detailModal = false" />
        </x-slot:actions>
    </x-modal>
</div>
