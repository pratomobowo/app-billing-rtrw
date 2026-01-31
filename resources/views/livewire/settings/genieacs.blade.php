<div>
    <div class="flex items-center gap-2 mb-6 text-slate-400">
        <a href="/settings" wire:navigate class="hover:text-primary transition-colors">Pengaturan</a>
        <span class="material-symbols-outlined text-sm">chevron_right</span>
        <span class="text-slate-800 font-bold">GenieACS (TR-069)</span>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 md:p-8 border-b border-slate-50">
                <div class="flex items-center gap-4 mb-2">
                    <div class="size-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">satellite_alt</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-800">Integrasi GenieACS</h3>
                        <p class="text-slate-500 text-sm">Konfigurasi Northbound Interface (NBI) GenieACS.</p>
                    </div>
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
