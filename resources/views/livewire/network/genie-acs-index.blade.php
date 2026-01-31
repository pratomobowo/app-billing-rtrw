<div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Modem TR-069 (GenieACS)</h2>
            <p class="text-sm text-slate-500">Manajemen perangkat ONT/Modem secara remote melalui protokol TR-069</p>
        </div>
        <div class="flex flex-col sm:flex-row w-full sm:w-auto gap-3">
             <div class="relative w-full sm:w-auto">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari Serial Number..." 
                    class="pl-10 pr-4 py-2 w-full sm:w-64 rounded-lg bg-white border border-slate-200 text-sm focus:ring-2 focus:ring-primary focus:border-primary text-slate-700 transition-all shadow-sm">
             </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        @if(count($devices) > 0)
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="text-slate-400 text-[10px] uppercase font-bold tracking-widest border-b border-slate-50">
                            <th class="bg-transparent py-4">Serial Number</th>
                            <th class="bg-transparent py-4">Hardware / Software</th>
                            <th class="bg-transparent py-4 text-center">Status</th>
                            <th class="bg-transparent py-4">IP Address</th>
                            <th class="bg-transparent py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($devices as $device)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 font-black text-slate-700">
                                    {{ $device['_serialNumber'] ?? 'N/A' }}
                                </td>
                                <td class="py-4">
                                    <span class="text-xs font-bold text-slate-600 block">{{ $device['VirtualParameters.HardwareVersion']['_value'] ?? '--' }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $device['VirtualParameters.SoftwareVersion']['_value'] ?? '--' }}</span>
                                </td>
                                <td class="py-4 text-center">
                                    @php
                                        $lastInform = isset($device['_lastInform']) ? \Carbon\Carbon::parse($device['_lastInform']) : null;
                                        $isOnline = $lastInform && $lastInform->diffInSeconds(now()) < 300;
                                    @endphp
                                    <div class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black {{ $isOnline ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                        <div class="size-1.5 rounded-full mr-1.5 {{ $isOnline ? 'bg-green-500' : 'bg-slate-400' }}"></div>
                                        {{ $isOnline ? 'ONLINE' : 'OFFLINE' }}
                                    </div>
                                </td>
                                <td class="py-4 font-mono text-xs text-slate-600">
                                    {{ $device['_ip'] ?? '--' }}
                                </td>
                                <td class="py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <x-button icon="o-pencil" class="btn-sm btn-ghost" />
                                        <x-button icon="o-trash" class="btn-sm btn-ghost text-red-500" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center py-20">
                <div class="size-20 bg-orange-50 text-orange-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="material-symbols-outlined text-4xl">satellite_alt</span>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Belum ada perangkat</h3>
                <p class="text-slate-500 max-w-md mx-auto mb-8">
                    Sistem terhubung ke GenieACS. Perangkat akan muncul di sini secara otomatis setelah terdaftar di ACS server.
                </p>
                <div class="flex justify-center gap-4">
                    <x-button label="Refresh Data" icon="o-arrow-path" class="btn-ghost" />
                    <x-button label="Panduan TR-069" icon="o-book-open" class="btn-primary" />
                </div>
            </div>
        @endif
    </div>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Total Perangkat</p>
            <h4 class="text-2xl font-black text-slate-800">--</h4>
        </div>
        <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm">
            <p class="text-emerald-500 text-xs font-bold uppercase tracking-widest mb-1">Online</p>
            <h4 class="text-2xl font-black text-slate-800">--</h4>
        </div>
        <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Menunggu Tugas</p>
            <h4 class="text-2xl font-black text-slate-800">--</h4>
        </div>
    </div>
</div>
