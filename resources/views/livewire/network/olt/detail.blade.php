<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <a href="/network/olt" wire:navigate class="hover:text-primary">OLT Management</a>
                <span>/</span>
                <span>{{ $olt->name }}</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-800">Daftar ONU Pelanggan</h2>
            <p class="text-sm text-slate-500">{{ $olt->type }} - {{ $olt->ip_address }}</p>
        </div>
        <x-button label="Refresh Semua Sinyal" icon="o-arrow-path" class="btn-primary" wire:click="refreshAll" spinner />
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-sm uppercase">
                        <th>Nama Pelanggan</th>
                        <th>Serial Number</th>
                        <th>Interface</th>
                        <th>Signal (Redaman)</th>
                        <th>Last Check</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($onus as $onu)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="font-bold text-slate-700">{{ $onu->name }}</td>
                            <td class="font-mono text-xs">{{ $onu->serial_number }}</td>
                            <td><span class="badge badge-ghost">{{ $onu->interface ?? 'N/A' }}</span></td>
                            <td>
                                @if($onu->signal)
                                    @php
                                        $color = $onu->signal >= -24 ? 'text-green-600 bg-green-50 border-green-200' : 
                                                ($onu->signal >= -27 ? 'text-yellow-600 bg-yellow-50 border-yellow-200' : 'text-red-600 bg-red-50 border-red-200');
                                    @endphp
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-1 rounded-lg border text-sm font-bold font-mono {{ $color }}">
                                            {{ $onu->signal }} dBm
                                        </span>
                                    </div>
                                @else
                                    <span class="text-slate-400 text-xs italic">Belum dicek</span>
                                @endif
                            </td>
                            <td class="text-xs text-slate-500">
                                {{ $onu->last_check ? $onu->last_check->diffForHumans() : '-' }}
                            </td>
                            <td>
                                <x-button icon="o-arrow-path" size="sm" class="btn-ghost" wire:click="refreshSignal({{ $onu->id }})" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-500">Belum ada ONU yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
