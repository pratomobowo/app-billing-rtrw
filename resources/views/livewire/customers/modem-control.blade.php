<div class="mt-4">
    @if($customer->modem_serial_number)
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center">
                        <span class="material-symbols-outlined">satellite_alt</span>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-bold uppercase tracking-widest">Status Modem (TR-069)</p>
                        <h4 class="font-bold text-slate-800">{{ $customer->modem_serial_number }} ({{ $customer->modem_model ?? 'Unknown Model' }})</h4>
                    </div>
                </div>
                <div>
                    @if(!$device)
                        <x-button label="Check Status" icon="o-magnifying-glass" wire:click="loadDeviceInfo" spinner="loadDeviceInfo" class="btn-sm btn-outline border-slate-200" />
                    @else
                        <div class="flex gap-2">
                             <x-button label="Reboot" icon="o-arrow-path" wire:click="reboot" spinner="reboot" class="btn-sm btn-warning" />
                             <x-button icon="o-ellipsis-vertical" class="btn-sm btn-ghost" />
                        </div>
                    @endif
                </div>
            </div>

            @if($device)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-slate-200">
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">Status</p>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ isset($device['_lastInform']) && now()->diffInSeconds($device['_lastInform']) < 300 ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                            {{ isset($device['_lastInform']) && now()->diffInSeconds($device['_lastInform']) < 300 ? 'ONLINE' : 'OFFLINE' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">Uptime</p>
                        <p class="text-xs font-bold text-slate-700">
                            {{-- This would need parsing from device parameters --}}
                            --
                        </p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-[10px] text-slate-400 font-bold uppercase">IP Address</p>
                        <p class="text-xs font-mono font-bold text-slate-700">{{ $device['_ip'] ?? '--' }}</p>
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="p-4 text-center rounded-xl border border-dashed border-slate-200 flex flex-col items-center gap-2">
            <span class="material-symbols-outlined text-slate-300">hide_source</span>
            <p class="text-xs text-slate-400 italic">Serial number modem belum diisi. Silakan isi di menu Edit.</p>
        </div>
    @endif
</div>
