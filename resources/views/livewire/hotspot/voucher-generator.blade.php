<div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Manajemen Voucher Hotspot</h2>
            <p class="text-sm text-slate-500">Generate voucher masal untuk sistem Hotspot</p>
        </div>
        <div class="flex gap-3">
             <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari kode..." 
                    class="pl-10 pr-4 py-2 w-64 rounded-lg bg-white border border-slate-200 text-sm focus:ring-2 focus:ring-primary focus:border-primary text-slate-700 transition-all shadow-sm">
             </div>
            <button wire:click="create" class="flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm">
                <span class="material-symbols-outlined text-[20px]">confirmation_number</span> Generate Voucher
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase tracking-wider text-xs border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Kode Voucher</th>
                        <th class="px-6 py-4">Profil</th>
                        <th class="px-6 py-4">Router</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Dibuat Pada</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($vouchers as $v)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="bg-slate-100 text-slate-800 font-mono font-bold px-3 py-1 rounded text-base border border-slate-200 select-all">
                                    {{ $v->code }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-700">{{ $v->profile->name }}</div>
                                <div class="text-[10px] text-slate-400 uppercase tracking-tighter">{{ $v->profile->bandwidth_limit }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $v->router->name }}</td>
                            <td class="px-6 py-4 text-slate-600 text-center">
                                @if($v->status === 'unused')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-700">Tersedia</span>
                                @elseif($v->status === 'used')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-700">Terpakai</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-500">Expired</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-xs">{{ $v->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <button wire:confirm="Hapus voucher ini akan menghapusnya dari Mikrotik. Lanjutkan?" 
                                        wire:click="delete({{ $v->id }})" 
                                        class="p-1.5 hover:bg-red-50 text-red-600 rounded-md transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-4xl text-slate-300">confirmation_number</span>
                                    <p>Belum ada voucher. Silakan generate baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($vouchers->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $vouchers->links() }}
            </div>
        @endif
    </div>

    <!-- Generator Modal -->
    <div x-data="{ open: @entangle('generatorModal') }" x-show="open" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         x-cloak>
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl" @click.away="open = false">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-primary rounded-t-2xl">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                   <span class="material-symbols-outlined text-white">magic_button</span> Generate Voucher Masal
                </h3>
                <button @click="open = false" class="text-white/70 hover:text-white transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form wire:submit="generate" class="p-6 space-y-4">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pilih Router</label>
                        <select wire:model="router_id" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                            <option value="">-- Pilih Router --</option>
                            @foreach($routers as $r)
                                <option value="{{ $r->id }}">{{ $r->name }} ({{ $r->ip_address }})</option>
                            @endforeach
                        </select>
                        @error('router_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Profil Voucher</label>
                        <select wire:model="voucher_profile_id" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                            <option value="">-- Pilih Profil --</option>
                            @foreach($profiles as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} - Rp {{ number_format($p->price, 0, ',', '.') }}</option>
                            @endforeach
                        </select>
                        @error('voucher_profile_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jumlah Voucher</label>
                            <input wire:model="count" type="number" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                            @error('count') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Keterangan</label>
                            <input wire:model="comment" type="text" placeholder="opsional" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 mt-6">
                    <button type="button" @click="open = false" class="px-5 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 rounded-lg transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2 text-sm font-semibold bg-primary hover:bg-primary-dark text-white rounded-lg transition-all shadow-sm flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">bolt</span> Mulai Generate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
