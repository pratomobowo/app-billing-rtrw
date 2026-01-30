<div>
    <h2 class="text-2xl font-bold text-slate-800 mb-6">Konfigurasi Sistem</h2>
    
        </a>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Application Settings -->
        <a href="/settings/application" wire:navigate class="block bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all group">
            <div class="bg-purple-50 p-3 rounded-lg w-fit mb-4 group-hover:bg-purple-100 transition-colors">
                <span class="material-symbols-outlined text-purple-600 text-3xl">settings_applications</span>
            </div>
            <h3 class="font-bold text-slate-800 text-lg mb-2">Aplikasi</h3>
            <p class="text-slate-500 text-sm">Pengaturan umum aplikasi, logo, dan nama perusahaan.</p>
        </a>

        <!-- Network Settings -->
        <a href="/settings/network" wire:navigate class="block bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all group">
            <div class="bg-blue-50 p-3 rounded-lg w-fit mb-4 group-hover:bg-blue-100 transition-colors">
                <span class="material-symbols-outlined text-blue-600 text-3xl">router</span>
            </div>
            <h3 class="font-bold text-slate-800 text-lg mb-2">Jaringan</h3>
            <p class="text-slate-500 text-sm">Konfigurasi Radius, isolir pelanggan, dan sinkronisasi Mikrotik.</p>
        </a>

        <!-- Payment Settings -->
        <a href="/settings/payment" wire:navigate class="block bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all group">
            <div class="bg-emerald-50 p-3 rounded-lg w-fit mb-4 group-hover:bg-emerald-100 transition-colors">
                <span class="material-symbols-outlined text-emerald-600 text-3xl">payments</span>
            </div>
            <h3 class="font-bold text-slate-800 text-lg mb-2">Pembayaran</h3>
            <p class="text-slate-500 text-sm">Metode pembayaran, gateway (Misal: Midtrans), dan biaya admin.</p>
        </a>
    </div>
</div>
