<div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Radius Monitor</h2>
            <p class="text-sm text-slate-500">Live sessions and accounting data from Radius Server</p>
        </div>
        <div class="flex items-center gap-3">
             <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari user..." 
                    class="pl-10 pr-4 py-2 w-64 rounded-lg bg-white border border-slate-200 text-sm focus:ring-2 focus:ring-primary focus:border-primary text-slate-700 transition-all shadow-sm">
             </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase tracking-wider text-xs border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Username</th>
                        <th class="px-6 py-3">Start Time</th>
                        <th class="px-6 py-3">Stop Time</th>
                        <th class="px-6 py-3">IP Address</th>
                        <th class="px-6 py-3">Download</th>
                        <th class="px-6 py-3">Upload</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sessions as $s)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-3 text-slate-600 font-mono">{{ $s->radacctid }}</td>
                            <td class="px-6 py-3 font-medium text-slate-900">{{ $s->username }}</td>
                            <td class="px-6 py-3 text-slate-600">{{ $s->acctstarttime }}</td>
                            <td class="px-6 py-3 text-slate-600">
                                @if($s->acctstoptime)
                                    {{ $s->acctstoptime }}
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-slate-600 font-mono">{{ $s->framedipaddress }}</td>
                            <td class="px-6 py-3 text-slate-600">{{ number_format(($s->acctinputoctets ?? 0) / 1024 / 1024, 2) }} MB</td>
                            <td class="px-6 py-3 text-slate-600">{{ number_format(($s->acctoutputoctets ?? 0) / 1024 / 1024, 2) }} MB</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-4xl text-slate-300">history</span>
                                    <p>Belum ada data sesi Radius.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $sessions->links() }}
        </div>
    </div>
</div>
