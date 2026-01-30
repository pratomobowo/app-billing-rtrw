<div>
    <div class="mb-6 flex items-center gap-2">
        <a href="/settings" wire:navigate class="btn btn-sm btn-ghost">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h2 class="text-2xl font-bold text-slate-800">Pengaturan Jaringan</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Info -->
        <div class="lg:col-span-1">
            <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100">
                <div class="flex items-center gap-3 mb-4 text-blue-700">
                    <span class="material-symbols-outlined text-3xl">info</span>
                    <h3 class="font-bold text-lg">Informasi Jaringan</h3>
                </div>
                <p class="text-blue-600 text-sm leading-relaxed mb-4">
                    Pengaturan ini berlaku secara global untuk sinkronisasi ke Radius dan semua router Mikrotik yang terdaftar.
                </p>
                <ul class="space-y-3 text-sm text-blue-600">
                    <li class="flex gap-2">
                        <span class="material-symbols-outlined text-base">check_circle</span>
                        <span>Nama Group Isolir harus sama dengan Nama Profile di Mikrotik.</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="material-symbols-outlined text-base">check_circle</span>
                        <span>Rate Limit menggunakan format Mikrotik (contoh: 128k/128k atau 1M/1M).</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Settings Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-600">security_update_good</span>
                    Konfigurasi Pelanggan Terisolir
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input 
                        label="Nama Group/Profile Isolir" 
                        wire:model="radius_isolated_group" 
                        placeholder="Contoh: ISOLATED"
                        hint="Gunakan huruf besar tanpa spasi" 
                    />
                    
                    <x-input 
                        label="Rate Limit Isolir (rx/tx)" 
                        wire:model="radius_isolated_limit" 
                        placeholder="Contoh: 128k/128k"
                        hint="Bandwidth maksimal untuk user isolir" 
                    />
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
                    <x-button 
                        label="Simpan Pengaturan" 
                        class="btn-primary px-8" 
                        wire:click="save" 
                        spinner="save" 
                    />
                </div>
            </div>
        </div>
    </div>
</div>
