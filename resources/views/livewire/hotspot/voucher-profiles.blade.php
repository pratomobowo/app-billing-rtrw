<div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Profil Voucher Hotspot</h2>
            <p class="text-sm text-slate-500">Kelola profil kecepatan dan harga voucher</p>
        </div>
        <button wire:click="create" class="flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm">
            <span class="material-symbols-outlined text-[20px]">add</span> Profil Baru
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase tracking-wider text-xs border-b border-slate-100">
                    <tr>
                        @foreach($headers as $header)
                            <th class="px-6 py-4">{{ $header['label'] }}</th>
                        @endforeach
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($profiles as $p)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-slate-400 font-mono">{{ $p->id }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-700">{{ $p->name }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $p->bandwidth_limit }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $p->validity }}</td>
                            <td class="px-6 py-4 font-medium text-slate-900">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $p->shared_users }} User</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button wire:click="edit({{ $p->id }})" class="p-1.5 hover:bg-blue-50 text-blue-600 rounded-md transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <button wire:confirm="Hapus profil ini akan menghapus data di Mikrotik juga. Lanjutkan?" 
                                            wire:click="delete({{ $p->id }})" 
                                            class="p-1.5 hover:bg-red-50 text-red-600 rounded-md transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-4xl text-slate-300">layers_clear</span>
                                    <p>Belum ada profil voucher yang dibuat.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div x-data="{ open: @entangle('profileModal') }" x-show="open" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         x-cloak>
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl" @click.away="open = false">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xl font-bold text-slate-800">{{ $editingProfile ? 'Edit Profil' : 'Profil Voucher Baru' }}</h3>
                <button @click="open = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form wire:submit="save" class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Profil</label>
                        <input wire:model="name" type="text" placeholder="Misal: Paket 1 Hari" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                        @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Limit Bandwidth</label>
                        <input wire:model="bandwidth_limit" type="text" placeholder="1M/1M" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                        @error('bandwidth_limit') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Masa Aktif</label>
                        <input wire:model="validity" type="text" placeholder="1d" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                        @error('validity') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Harga (Rp)</label>
                        <input wire:model="price" type="number" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                        @error('price') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Shared Users</label>
                        <input wire:model="shared_users" type="number" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                        @error('shared_users') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 mt-6">
                    <button type="button" @click="open = false" class="px-5 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 rounded-lg transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2 text-sm font-semibold bg-primary hover:bg-primary-dark text-white rounded-lg transition-all shadow-sm">Simpan Profil</button>
                </div>
            </form>
        </div>
    </div>
</div>
