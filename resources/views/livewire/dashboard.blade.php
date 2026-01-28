<div>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <!-- Card 1 -->
        <div class="bg-white rounded-xl p-5 md:p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div class="bg-blue-50 p-3 rounded-lg text-primary">
                    <span class="material-symbols-outlined">group</span>
                </div>
                <span class="bg-green-100 text-green-700 text-[10px] md:text-xs font-semibold px-2 py-1 rounded-full">+100%</span>
            </div>
            <p class="text-slate-500 text-xs md:text-sm font-medium mb-1">Total Pelanggan</p>
            <h3 class="text-xl md:text-2xl font-bold text-slate-800">{{ number_format($totalCustomers) }}</h3>
        </div>
        
        <!-- Card 2 -->
        <div class="bg-white rounded-xl p-5 md:p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div class="bg-purple-50 p-3 rounded-lg text-purple-600">
                    <span class="material-symbols-outlined">wifi</span>
                </div>
                <span class="bg-green-100 text-green-700 text-[10px] md:text-xs font-semibold px-2 py-1 rounded-full">Aktif</span>
            </div>
            <p class="text-slate-500 text-xs md:text-sm font-medium mb-1">Pelanggan Aktif</p>
            <h3 class="text-xl md:text-2xl font-bold text-slate-800">{{ number_format($activeUsers) }}</h3>
        </div>
        
        <!-- Card 3 -->
        <div class="bg-white rounded-xl p-5 md:p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div class="bg-green-50 p-3 rounded-lg text-green-600">
                    <span class="material-symbols-outlined">payments</span>
                </div>
                <span class="bg-green-100 text-green-700 text-[10px] md:text-xs font-semibold px-2 py-1 rounded-full">Bulan Ini</span>
            </div>
            <p class="text-slate-500 text-xs md:text-sm font-medium mb-1">Total Pendapatan</p>
            <h3 class="text-xl md:text-2xl font-bold text-slate-800">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</h3>
        </div>
        
        <!-- Card 4 -->
        <div class="bg-white rounded-xl p-5 md:p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div class="bg-orange-50 p-3 rounded-lg text-orange-600">
                    <span class="material-symbols-outlined">pending_actions</span>
                </div>
                <span class="bg-red-100 text-red-700 text-[10px] md:text-xs font-semibold px-2 py-1 rounded-full">Pending</span>
            </div>
            <p class="text-slate-500 text-xs md:text-sm font-medium mb-1">Tagihan Belum Bayar</p>
            <h3 class="text-xl md:text-2xl font-bold text-slate-800">{{ number_format($pendingPayments) }}</h3>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="bg-white rounded-xl p-5 md:p-6 shadow-sm border border-slate-100 mt-6 md:mt-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Revenue Overview</h3>
                <p class="text-sm text-slate-500">Income performance over the last 7 days</p>
            </div>
            <div class="flex bg-slate-100 p-1 rounded-lg">
                <button class="px-3 py-1 text-xs font-medium bg-white text-slate-800 shadow-sm rounded-md">Weekly</button>
                <button class="px-3 py-1 text-xs font-medium text-slate-500 hover:text-slate-800 rounded-md">Monthly</button>
            </div>
        </div>
        <div class="w-full overflow-hidden overflow-x-auto">
            <div class="min-w-[500px] h-[250px] md:h-[300px] relative">
                <svg class="w-full h-full" fill="none" preserveaspectratio="none" viewbox="0 0 1200 300" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <lineargradient id="chartGradient" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%" stop-color="#137fec" stop-opacity="0.2"></stop>
                            <stop offset="100%" stop-color="#137fec" stop-opacity="0"></stop>
                        </lineargradient>
                    </defs>
                    <line stroke="#e2e8f0" stroke-width="1" x1="0" x2="1200" y1="299" y2="299"></line>
                    <line stroke="#e2e8f0" stroke-dasharray="4 4" stroke-width="1" x1="0" x2="1200" y1="225" y2="225"></line>
                    <line stroke="#e2e8f0" stroke-dasharray="4 4" stroke-width="1" x1="0" x2="1200" y1="150" y2="150"></line>
                    <line stroke="#e2e8f0" stroke-dasharray="4 4" stroke-width="1" x1="0" x2="1200" y1="75" y2="75"></line>
                    <path d="M0 250 C 100 240, 150 180, 200 190 S 300 120, 400 140 S 500 80, 600 90 S 700 160, 800 130 S 900 60, 1000 80 S 1100 40, 1200 50 L 1200 300 L 0 300 Z" fill="url(#chartGradient)"></path>
                    <path d="M0 250 C 100 240, 150 180, 200 190 S 300 120, 400 140 S 500 80, 600 90 S 700 160, 800 130 S 900 60, 1000 80 S 1100 40, 1200 50" fill="none" stroke="#137fec" stroke-linecap="round" stroke-width="3"></path>
                    @foreach([200, 400, 600, 800, 1000] as $x)
                        <circle cx="{{ $x }}" cy="{{ 190 - ($x/20) }}" fill="#ffffff" r="4" stroke="#137fec" stroke-width="2"></circle>
                    @endforeach
                </svg>
                <div class="flex justify-between mt-2 px-2 text-[10px] md:text-xs font-medium text-slate-400">
                    <span>Mon</span>
                    <span>Tue</span>
                    <span>Wed</span>
                    <span>Thu</span>
                    <span>Fri</span>
                    <span>Sat</span>
                    <span>Sun</span>
                </div>
            </div>
        </div>
    </div>
</div>
