<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Status & Kesehatan Router</h2>
            <p class="text-slate-500 text-sm mt-1">Pantau performa hardware dan log sistem Mikrotik Anda.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <x-select 
                wire:model.live="router_id" 
                :options="$routers" 
                placeholder="Pilih Router"
                class="select-sm w-48"
            />
            
            <x-button 
                label="Refresh" 
                icon="o-arrow-path" 
                class="btn-primary btn-sm" 
                wire:click="refreshData" 
                spinner="refreshData" 
            />
        </div>
    </div>

    <!-- Health Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @if($resources)
            <!-- CPU Load -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">CPU Load</span>
                    <span class="material-symbols-outlined text-slate-300">memory</span>
                </div>
                <div class="flex items-end justify-between mb-2">
                    <h4 class="text-3xl font-bold text-slate-800">{{ $resources['cpu_load'] }}%</h4>
                    <span class="text-xs text-slate-400">{{ $resources['cpu_count'] }} Core</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2">
                    <div class="rounded-full h-2 transition-all duration-500 {{ $resources['cpu_load'] > 80 ? 'bg-red-500' : ($resources['cpu_load'] > 50 ? 'bg-yellow-500' : 'bg-primary-500') }}" 
                         style="width: {{ $resources['cpu_load'] }}%"></div>
                </div>
            </div>

            <!-- Memory Usage -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                @php
                    $memUsed = $resources['total_memory'] - $resources['free_memory'];
                    $memPercent = $resources['total_memory'] > 0 ? round(($memUsed / $resources['total_memory']) * 100) : 0;
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">RAM Usage</span>
                    <span class="material-symbols-outlined text-slate-300">rebase_edit</span>
                </div>
                <div class="flex items-end justify-between mb-2">
                    <h4 class="text-3xl font-bold text-slate-800">{{ $memPercent }}%</h4>
                    <span class="text-xs text-slate-400">{{ round($memUsed / 1024 / 1024, 1) }} / {{ round($resources['total_memory'] / 1024 / 1024, 1) }} MB</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2">
                    <div class="rounded-full h-2 bg-blue-500 transition-all duration-500" style="width: {{ $memPercent }}%"></div>
                </div>
            </div>

            <!-- HDD Space -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                @php
                    $hddUsed = $resources['total_hdd'] - $resources['free_hdd'];
                    $hddPercent = $resources['total_hdd'] > 0 ? round(($hddUsed / $resources['total_hdd']) * 100) : 0;
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">HDD Usage</span>
                    <span class="material-symbols-outlined text-slate-300">database</span>
                </div>
                <div class="flex items-end justify-between mb-2">
                    <h4 class="text-3xl font-bold text-slate-800">{{ $hddPercent }}%</h4>
                    <span class="text-xs text-slate-400">{{ round($hddUsed / 1024 / 1024, 1) }} / {{ round($resources['total_hdd'] / 1024 / 1024, 1) }} MB</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2">
                    <div class="rounded-full h-2 bg-amber-500 transition-all duration-500" style="width: {{ $hddPercent }}%"></div>
                </div>
            </div>

            <!-- Uptime/System -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">System Information</span>
                    <span class="material-symbols-outlined text-slate-300">info</span>
                </div>
                <div class="space-y-1">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-400">Board:</span>
                        <span class="font-bold text-slate-700">{{ $resources['board_name'] }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-400">Uptime:</span>
                        <span class="font-bold text-slate-700">{{ $resources['uptime'] }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-400">Version:</span>
                        <span class="font-bold text-slate-700">{{ $resources['version'] }}</span>
                    </div>
                </div>
            </div>
        @else
            <div class="col-span-1 md:col-span-4 bg-red-50 border border-red-100 rounded-2xl p-8 text-center">
                <span class="material-symbols-outlined text-red-400 text-5xl mb-2">router</span>
                <p class="text-red-700 font-bold">Router tidak terjangkau</p>
                <p class="text-red-500 text-sm">Pastikan koneksi API Mikrotik sudah benar dan router dalam keadaan hidup.</p>
            </div>
        @endif
    </div>

    <!-- Logs Section -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="font-bold text-slate-800">Log Sistem Terbaru</h3>
            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                Auto Update (10s)
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="table table-compact w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="w-32 text-slate-600 font-bold uppercase text-[10px] tracking-wider py-4 px-6 border-b border-slate-100">Waktu</th>
                        <th class="w-48 text-slate-600 font-bold uppercase text-[10px] tracking-wider py-4 px-6 border-b border-slate-100">Topik</th>
                        <th class="text-slate-600 font-bold uppercase text-[10px] tracking-wider py-4 px-6 border-b border-slate-100">Pesan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paginatedLogs as $log)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-3 text-xs text-slate-500 font-mono">
                                {{ $log['time'] }}
                            </td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-600 border border-slate-200">
                                    {{ $log['topics'] }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-xs text-slate-700">
                                {{ $log['message'] }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-12 text-slate-400 text-sm italic">
                                Tidak ada data log yang tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $paginatedLogs->links() }}
        </div>
    </div>

    <div wire:poll.10s="refreshData"></div>
</div>
