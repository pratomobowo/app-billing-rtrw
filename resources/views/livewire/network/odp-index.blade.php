<div>
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-black text-slate-800">Daftar ODP (Optical Distribution Point)</h3>
        <button wire:click="create" class="btn btn-primary btn-sm rounded-xl">
            <span class="material-symbols-outlined">add</span> Tambah ODP
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr class="text-slate-400 text-[10px] uppercase font-bold tracking-widest border-b border-slate-50">
                    <th class="bg-transparent py-4 text-center">#</th>
                    <th class="bg-transparent py-4">Nama ODP</th>
                    <th class="bg-transparent py-4">Source ODC</th>
                    <th class="bg-transparent py-4 text-center">Port Terpakai</th>
                    <th class="bg-transparent py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($odps as $odp)
                    @php
                        $usedCount = $odp->onus->count();
                        $pct = $odp->capacity > 0 ? ($usedCount / $odp->capacity) * 100 : 0;
                        $statusColor = $pct >= 90 ? 'text-red-600 bg-red-50' : ($pct >= 70 ? 'text-orange-600 bg-orange-50' : 'text-emerald-600 bg-emerald-50');
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="text-center py-4 text-slate-400 font-mono text-xs">{{ $odp->id }}</td>
                        <td class="py-4">
                            <span class="font-bold text-slate-700 block">{{ $odp->name }}</span>
                            <span class="text-[10px] text-slate-400 font-medium">{{ $odp->description ?: 'No description' }}</span>
                        </td>
                        <td class="py-4 font-medium text-slate-600">
                             <span class="material-symbols-outlined text-[14px] align-middle">account_tree</span>
                             {{ $odp->odc->name }}
                        </td>
                        <td class="py-4">
                            <div class="flex flex-col items-center gap-1">
                                <span class="px-3 py-1 {{ $statusColor }} rounded-full text-xs font-black">
                                    {{ $usedCount }}/{{ $odp->capacity }} Port
                                </span>
                                <div class="w-16 bg-slate-100 rounded-full h-1 overflow-hidden">
                                     <div class="h-full bg-primary" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button wire:click="edit({{ $odp->id }})" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Edit">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>
                                <button onclick="confirm('Hapus ODP ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $odp->id }})" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400">
                            <span class="material-symbols-outlined text-4xl mb-2 opacity-20">hub</span>
                            <p class="text-xs font-medium">Belum ada data ODP</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-6">
            {{ $odps->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    <x-modal wire:model="odpModal" title="{{ $editingOdp ? 'Edit ODP' : 'Tambah ODP Baru' }}" separator>
        <div class="grid grid-cols-1 gap-4">
            <x-input label="Nama ODP" wire:model="name" placeholder="Contoh: ODP-PUSAT-01-01" required />
            <x-select label="Source ODC" wire:model="odc_id" :options="$odcs" option-label="name" option-value="id" placeholder="Pilih ODC" />
            
            <x-input label="Kapasitas Port" wire:model="capacity" type="number" min="1" hint="Jumlah total port pada splitter ODP" />

            <div class="grid grid-cols-2 gap-4">
                <x-input label="Latitude" wire:model="latitude" placeholder="-6.200000" />
                <x-input label="Longitude" wire:model="longitude" placeholder="106.816666" />
            </div>

            <x-textarea label="Keterangan" wire:model="description" placeholder="Lokasi spesifik atau catatan lainnya..." />
        </div>

        <x-slot:actions>
            <x-button label="Batal" @click="$wire.odpModal = false" />
            <x-button label="Simpan ODP" class="btn-primary" wire:click="save" />
        </x-slot:actions>
    </x-modal>
</div>
