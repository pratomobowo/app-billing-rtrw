<div>
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Log Mikrotik</h2>
            <p class="text-slate-500 text-sm mt-1">Pantau aktivitas router secara real-time.</p>
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
                wire:click="refreshLogs" 
                spinner="refreshLogs" 
            />
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table table-compact w-full">
                <thead class="bg-slate-50">
                    <tr>
                        @foreach($headers as $header)
                            <th class="{{ $header['class'] ?? '' }} text-slate-600 font-bold uppercase text-[10px] tracking-wider py-4 px-6 border-b border-slate-100">
                                {{ $header['label'] }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-200/50 transition-colors group">
                            <td class="px-6 py-3 text-sm text-slate-500 font-mono">
                                {{ $log['time'] }}
                            </td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-600 border border-slate-200">
                                    {{ $log['topics'] }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-sm text-slate-700">
                                {{ $log['message'] }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-12 text-slate-400">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-4xl opacity-20">history</span>
                                    <p>Tidak ada data log atau gagal terhubung ke router.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
