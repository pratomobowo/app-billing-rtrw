<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-xl font-black text-slate-800">Manajemen Wilayah</h3>
            <p class="text-xs text-slate-500">Kelompokkan pelanggan untuk mempermudah monitoring area.</p>
        </div>
        <button wire:click="create" class="btn btn-primary btn-sm rounded-xl">
            <span class="material-symbols-outlined">add</span> Tambah Wilayah
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr class="text-slate-400 text-[10px] uppercase font-bold tracking-widest border-b border-slate-50">
                        <th class="bg-transparent py-4 text-center">#</th>
                        <th class="bg-transparent py-4">Nama Wilayah / Kode</th>
                        <th class="bg-transparent py-4">Deskripsi</th>
                        <th class="bg-transparent py-4 text-center">Total Pelanggan</th>
                        <th class="bg-transparent py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($areas as $area)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="text-center py-4 text-slate-400 font-mono text-xs">{{ $area->id }}</td>
                            <td class="py-4">
                                <span class="font-bold text-slate-700 block text-sm">{{ $area->name }}</span>
                                <span class="text-[10px] bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded font-black uppercase">{{ $area->code }}</span>
                            </td>
                            <td class="py-4 text-xs text-slate-500 italic">
                                {{ $area->description ?: 'Tidak ada deskripsi' }}
                            </td>
                            <td class="py-4 text-center">
                                <span class="badge badge-outline border-slate-200 text-slate-600 font-bold px-3 py-3">
                                    {{ $area->customers_count }} Pelanggan
                                </span>
                            </td>
                            <td class="py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button wire:click="edit({{ $area->id }})" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Edit">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </button>
                                    <button onclick="confirm('Hapus wilayah ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $area->id }})" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <span class="material-symbols-outlined text-4xl mb-2 opacity-20">map</span>
                                <p class="text-xs font-medium">Belum ada data wilayah</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-50">
            {{ $areas->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    <x-modal wire:model="areaModal" title="{{ $editingArea ? 'Edit Wilayah' : 'Tambah Wilayah Baru' }}" separator>
        <div class="grid grid-cols-1 gap-4">
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-2">
                    <x-input label="Nama Wilayah" wire:model="name" placeholder="Contoh: Pusat Kota" required />
                </div>
                <div>
                    <x-input label="Kode" wire:model="code" placeholder="PST" required hint="Kode singkat unik" />
                </div>
            </div>
            
            <x-textarea label="Deskripsi (Opsional)" wire:model="description" placeholder="Keterangan area atau cakupan wilayah..." />
        </div>

        <x-slot:actions>
            <x-button label="Batal" @click="$wire.areaModal = false" />
            <x-button label="Simpan Wilayah" class="btn-primary" wire:click="save" spinner="save" />
        </x-slot:actions>
    </x-modal>
</div>
