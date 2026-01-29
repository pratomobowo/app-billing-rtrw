<div class="p-4 md:p-8 max-w-5xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dokumentasi Fitur</h1>
        <p class="text-gray-600">Panduan lengkap penggunaan aplikasi RT RW Net Billing.</p>
    </div>

    <div class="space-y-6" x-data="{ activeAccordion: null }">
        <!-- Dashboard & Monitoring -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <button 
                @click="activeAccordion = activeAccordion === 1 ? null : 1"
                class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors"
                :class="{ 'bg-gray-50': activeAccordion === 1 }"
            >
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                        <x-mary-icon name="o-home" class="w-6 h-6" />
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Dashboard & Monitoring</h3>
                        <p class="text-sm text-gray-500">Statistik real-time dan performa jaringan.</p>
                    </div>
                </div>
                <x-mary-icon name="o-chevron-down" class="w-5 h-5 text-gray-400 transition-transform" ::class="{ 'rotate-180': activeAccordion === 1 }" />
            </button>
            <div x-show="activeAccordion === 1" x-collapse>
                <div class="p-5 border-t border-gray-100 prose prose-blue max-w-none">
                    <p>Halaman utama yang memberikan gambaran ringkas tentang operasional harian Anda:</p>
                    <ul>
                        <li><strong>Statistik Pelanggan:</strong> Jumlah pelanggan aktif, terisolasi, dan total pendapatan bulan ini.</li>
                        <li><strong>Monitoring Traffic:</strong> Visualisasi real-time bandwidth penggunaan router Mikrotik menggunakan grafik interaktif.</li>
                        <li><strong>Notifikasi Sistem:</strong> Peringatan dini jika terjadi masalah pada koneksi OLT atau Router.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Pelanggan & Paket -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <button 
                @click="activeAccordion = activeAccordion === 2 ? null : 2"
                class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors"
                :class="{ 'bg-gray-50': activeAccordion === 2 }"
            >
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-green-50 rounded-lg text-green-600">
                        <x-mary-icon name="o-users" class="w-6 h-6" />
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Pelanggan & Paket</h3>
                        <p class="text-sm text-gray-500">Manajemen data pelanggan dan paket internet.</p>
                    </div>
                </div>
                <x-mary-icon name="o-chevron-down" class="w-5 h-5 text-gray-400 transition-transform" ::class="{ 'rotate-180': activeAccordion === 2 }" />
            </button>
            <div x-show="activeAccordion === 2" x-collapse>
                <div class="p-5 border-t border-gray-100 prose prose-blue max-w-none">
                    <h5>Pelanggan:</h5>
                    <ul>
                        <li>Pendaftaran pelanggan dengan koordinat lokasi (Latitude/Longitude) untuk pemetaan.</li>
                        <li>Status <strong>Aktif</strong> atau <strong>Terisolasi</strong> untuk mengontrol akses internet secara otomatis.</li>
                        <li>Informasi PPPoE akun yang tersinkronisasi langsung dengan Radius Server.</li>
                    </ul>
                    <h5>Paket Internet:</h5>
                    <ul>
                        <li>Pengaturan <strong>Bandwidth Limit</strong> (contoh: 10M/10M) yang akan diterapkan di Radius Profiles.</li>
                        <li>Manajemen harga paket yang terhubung dengan perhitungan invoice otomatis.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Infrastruktur Jaringan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <button 
                @click="activeAccordion = activeAccordion === 3 ? null : 3"
                class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors"
                :class="{ 'bg-gray-50': activeAccordion === 3 }"
            >
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                        <x-mary-icon name="o-folder-open" class="w-6 h-6" />
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Infrastruktur & Peta</h3>
                        <p class="text-sm text-gray-500">Pemetaan ODP dan monitoring OLT/ONU.</p>
                    </div>
                </div>
                <x-mary-icon name="o-chevron-down" class="w-5 h-5 text-gray-400 transition-transform" ::class="{ 'rotate-180': activeAccordion === 3 }" />
            </button>
            <div x-show="activeAccordion === 3" x-collapse>
                <div class="p-5 border-t border-gray-100 prose prose-blue max-w-none">
                    <ul>
                        <li><strong>OLT Management:</strong> Monitoring status perangkat OLT (ZTE/Huawei) dan daftar ONU yang terkoneksi.</li>
                        <li><strong>ODP Management:</strong> Pencatatan kotak ODP berserta kapasitas port dan lokasinya.</li>
                        <li><strong>Peta Sebaran:</strong> Visualisasi geografis lokasi ODP dan Pelanggan menggunakan Leaflet.js untuk memudahkan teknisi di lapangan.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Keuangan & Pembayaran -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <button 
                @click="activeAccordion = activeAccordion === 4 ? null : 4"
                class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors"
                :class="{ 'bg-gray-50': activeAccordion === 4 }"
            >
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                        <x-mary-icon name="o-credit-card" class="w-6 h-6" />
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Keuangan & Pembayaran</h3>
                        <p class="text-sm text-gray-500">Invoice, payment gateway, dan otomasi billing.</p>
                    </div>
                </div>
                <x-mary-icon name="o-chevron-down" class="w-5 h-5 text-gray-400 transition-transform" ::class="{ 'rotate-180': activeAccordion === 4 }" />
            </button>
            <div x-show="activeAccordion === 4" x-collapse>
                <div class="p-5 border-t border-gray-100 prose prose-blue max-w-none">
                    <ul>
                        <li><strong>Otomasi Invoice:</strong> Pembuatan tagihan otomatis setiap bulan berdasarkan tanggal jatuh tempo pelanggan.</li>
                        <li><strong>Payment Gateway:</strong> Integrasi dengan Midtrans/Xendit untuk pembayaran otomatis via Transfer Bank, QRIS, atau E-Wallet.</li>
                        <li><strong>WhatsApp Billing:</strong> Pengiriman otomatis link tagihan ke nomor WhatsApp pelanggan melalui layanan Gowa.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- WhatsApp Integration -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <button 
                @click="activeAccordion = activeAccordion === 5 ? null : 5"
                class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors"
                :class="{ 'bg-gray-50': activeAccordion === 5 }"
            >
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600">
                        <x-mary-icon name="o-chat-bubble-left-right" class="w-6 h-6" />
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Integrasi WhatsApp (Gowa)</h3>
                        <p class="text-sm text-gray-500">Notifikasi dan interaksi otomatis via WA.</p>
                    </div>
                </div>
                <x-mary-icon name="o-chevron-down" class="w-5 h-5 text-gray-400 transition-transform" ::class="{ 'rotate-180': activeAccordion === 5 }" />
            </button>
            <div x-show="activeAccordion === 5" x-collapse>
                <div class="p-5 border-t border-gray-100 prose prose-blue max-w-none">
                    <ul>
                        <li><strong>Device Management:</strong> Hubungkan nomor WhatsApp Anda dengan memindai QR Code Gowa langsung dari dashboard.</li>
                        <li><strong>Broadcast:</strong> Kirim pesan ke banyak pelanggan sekaligus untuk informasi pemeliharaan atau promo.</li>
                        <li><strong>Log Percakapan:</strong> Riwayat pesan yang dikirim oleh sistem (Tagihan, Konfirmasi Pembayaran).</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Radius & Mikrotik Logic -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <button 
                @click="activeAccordion = activeAccordion === 6 ? null : 6"
                class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors"
                :class="{ 'bg-gray-50': activeAccordion === 6 }"
            >
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-gray-50 rounded-lg text-gray-600">
                        <x-mary-icon name="o-server" class="w-6 h-6" />
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Sistem (Radius & Mikrotik)</h3>
                        <p class="text-sm text-gray-500">Penjelasan sinkronisasi teknis.</p>
                    </div>
                </div>
                <x-mary-icon name="o-chevron-down" class="w-5 h-5 text-gray-400 transition-transform" ::class="{ 'rotate-180': activeAccordion === 6 }" />
            </button>
            <div x-show="activeAccordion === 6" x-collapse>
                <div class="p-5 border-t border-gray-100 prose prose-blue max-w-none">
                    <p>Bagaimana sistem ini mengelola jaringan Anda:</p>
                    <ul>
                        <li><strong>Radius Sync:</strong> Setiap kali data pelanggan atau paket diubah, sistem memperbarui tabel <code>radcheck</code>, <code>radgroupreply</code>, dan <code>radusergroup</code> secara otomatis.</li>
                        <li><strong>Isolation Logic:</strong> Pelanggan yang belum membayar setelah jatuh tempo akan dipindahkan ke group <code>ISOLATED</code> di Radius, yang biasanya dikonfigurasi di Mikrotik untuk diarahkan ke halaman isolasi (WallGarden).</li>
                        <li><strong>Session Kick:</strong> Sistem mengirimkan perintah <code>kick</code> ke Mikrotik saat status pelanggan berubah, memaksa perangkat re-login untuk menerapkan profil terbaru.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Friendly Tip -->
    <div class="mt-12 p-4 bg-yellow-50 rounded-lg border border-yellow-100 flex items-start space-x-3">
        <x-mary-icon name="o-information-circle" class="w-5 h-5 text-yellow-600 mt-0.5" />
        <p class="text-sm text-yellow-700">
            <strong>Tips Mobile:</strong> Halaman ini dioptimalkan untuk perangkat seluler. Anda dapat mengetuk setiap bagian untuk membuka atau menutup informasi yang diperlukan.
        </p>
    </div>
</div>
