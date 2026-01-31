    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">GenieACS (TR-069)</h2>
            <p class="text-sm text-slate-500">Konfigurasi Northbound Interface (NBI) GenieACS</p>
        </div>
        <div>
             <a href="/settings" wire:navigate class="btn btn-ghost btn-sm">
                <span class="material-symbols-outlined mr-2">arrow_back</span> Kembali
             </a>
        </div>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="size-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">satellite_alt</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">Konfigurasi Server</h3>
                        <p class="text-slate-500 text-xs">Pastikan URL NBI dapat dijangkau oleh server billing.</p>
                    </div>
                </div>

            <div class="p-6 md:p-8">
                <form wire:submit="save" class="space-y-6">
                    <x-input 
                        label="URL GenieACS NBI" 
                        wire:model="genieacs_url" 
                        placeholder="http://192.168.1.100:7557" 
                        hint="Gunakan format http://host:port (Default port: 7557)" 
                        icon="o-globe-alt"
                    />

                    <x-input 
                        label="Request Timeout (Detik)" 
                        wire:model="genieacs_timeout" 
                        type="number" 
                        min="1" 
                        max="60"
                        hint="Batas waktu tunggu respon API"
                        icon="o-clock"
                    />

                    <div class="flex justify-end pt-4">
                        <x-button label="Simpan Pengaturan" class="btn-primary rounded-xl" type="submit" spinner="save" />
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-8 p-6 rounded-2xl bg-blue-50 border border-blue-100 flex gap-4">
            <span class="material-symbols-outlined text-blue-600 text-3xl">info</span>
            <div class="text-sm text-blue-800 leading-relaxed">
                <p class="font-bold mb-1">Informasi Penting:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Pastikan GenieACS sudah terinstal dan NBI Container aktif.</li>
                    <li>IP Host harus dapat diakses oleh server Billing ini.</li>
                    <li>Manajemen jauh (TR-069) memungkinkan Anda mengatur SSID, PPPoE, dan reboot modem tanpa akses fisik.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
