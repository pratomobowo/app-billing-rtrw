<div>
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-black text-slate-800">Daftar ODC (Optical Distribution Center)</h3>
        <button wire:click="create" class="btn btn-primary btn-sm rounded-xl">
            <span class="material-symbols-outlined">add</span> Tambah ODC
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr class="text-slate-400 text-[10px] uppercase font-bold tracking-widest border-b border-slate-50">
                    <th class="bg-transparent py-4 text-center">#</th>
                    <th class="bg-transparent py-4">Nama ODC</th>
                    <th class="bg-transparent py-4">Source OLT</th>
                    <th class="bg-transparent py-4 text-center">Jumlah ODP</th>
                    <th class="bg-transparent py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($odcs as $odc)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="text-center py-4 text-slate-400 font-mono text-xs">{{ $odc->id }}</td>
                        <td class="py-4">
                            <span class="font-bold text-slate-700 block">{{ $odc->name }}</span>
                            <span class="text-[10px] text-slate-400 font-medium">{{ $odc->description ?: 'No description' }}</span>
                        </td>
                        <td class="py-4 font-medium text-slate-600">
                             <span class="material-symbols-outlined text-[14px] align-middle">settings_input_component</span>
                             {{ $odc->olt->name }}
                        </td>
                        <td class="py-4 text-center">
                            <span class="px-3 py-1 bg-purple-50 text-purple-600 rounded-full text-xs font-black">
                                {{ $odc->odps_count }} ODP
                            </span>
                        </td>
                        <td class="py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button wire:click="edit({{ $odc->id }})" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Edit">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>
                                <button onclick="confirm('Hapus ODC ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $odc->id }})" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400">
                            <span class="material-symbols-outlined text-4xl mb-2 opacity-20">account_tree</span>
                            <p class="text-xs font-medium">Belum ada data ODC</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-6">
            {{ $odcs->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    <x-modal wire:model="odcModal" title="{{ $editingOdc ? 'Edit ODC' : 'Tambah ODC Baru' }}" separator>
        <div class="grid grid-cols-1 gap-4">
            <x-input label="Nama ODC" wire:model="name" placeholder="Contoh: ODC-PUSAT-01" required />
            <x-select label="Source OLT" wire:model="olt_id" :options="$olts" option-label="name" option-value="id" placeholder="Pilih OLT" />
            
            <div class="grid grid-cols-2 gap-4">
                <x-input label="Latitude" wire:model="latitude" placeholder="-6.200000" />
                <x-input label="Longitude" wire:model="longitude" placeholder="106.816666" />
            </div>

            <x-textarea label="Keterangan" wire:model="description" placeholder="Lokasi spesifik atau catatan lainnya..." />
        </div>

        <x-slot:actions>
            <x-button label="Batal" @click="$wire.odcModal = false" />
            <x-button label="Simpan ODC" class="btn-primary" wire:click="save" />
        </x-slot:actions>
    </x-modal>
</div>
