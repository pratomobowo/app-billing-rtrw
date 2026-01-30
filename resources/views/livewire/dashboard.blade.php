<div>
    <!-- Top Stats Bar -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Keuangan: Pendapatan -->
        <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-all">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-5xl sm:text-6xl text-green-600">payments</span>
            </div>
            <p class="text-slate-500 text-[10px] sm:text-xs font-bold uppercase tracking-widest mb-1">Pendapatan Bulan Ini</p>
            <h3 class="text-xl sm:text-2xl font-black text-slate-800">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</h3>
            <div class="mt-3 sm:mt-4 flex items-center gap-2">
                <span class="flex items-center text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">
                    <span class="material-symbols-outlined text-sm">trending_up</span> Lunas
                </span>
                <span class="text-[10px] text-slate-400 font-medium hidden sm:inline">Dari invoice terbayar</span>
            </div>
        </div>

        <!-- Keuangan: Laba Bersih -->
        <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-all">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-5xl sm:text-6xl text-blue-600">account_balance_wallet</span>
            </div>
            <p class="text-slate-500 text-[10px] sm:text-xs font-bold uppercase tracking-widest mb-1">Estimasi Laba Bersih</p>
            <h3 class="text-xl sm:text-2xl font-black {{ $netProfit >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                Rp {{ number_format($netProfit, 0, ',', '.') }}
            </h3>
            <div class="mt-3 sm:mt-4 flex items-center gap-2">
                <span class="text-[10px] text-slate-400 font-medium font-mono">In: {{ number_format($monthlyIncome/1000, 0) }}k | Out: {{ number_format($monthlyExpense/1000, 0) }}k</span>
            </div>
        </div>

        <!-- Network Health -->
        <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-all">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-5xl sm:text-6xl text-purple-600">lan</span>
            </div>
            <p class="text-slate-500 text-[10px] sm:text-xs font-bold uppercase tracking-widest mb-1">Kesehatan Jaringan</p>
            <h3 class="text-xl sm:text-2xl font-black text-slate-800">{{ $onlineRouters }}/{{ $totalRouters }} <span class="text-[10px] sm:text-sm font-bold text-slate-400 tracking-tight">Router Online</span></h3>
            <div class="mt-3 sm:mt-4 flex items-center gap-1">
                @foreach($routerStatus as $status)
                    <div class="size-2 rounded-full {{ $status['status'] === 'online' ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.5)]' : 'bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.5)]' }}" title="{{ $status['name'] }}: {{ strtoupper($status['status']) }}"></div>
                @endforeach
            </div>
        </div>

        <!-- Pelanggan & Tagihan -->
        <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-all">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-5xl sm:text-6xl text-orange-600">group</span>
            </div>
            <p class="text-slate-500 text-[10px] sm:text-xs font-bold uppercase tracking-widest mb-1">Status Penagihan</p>
            <h3 class="text-xl sm:text-2xl font-black text-slate-800">{{ $pendingPayments }} <span class="text-[10px] sm:text-sm font-bold text-slate-400 tracking-tight">Menunggu Bayar</span></h3>
            <div class="mt-3 sm:mt-4 flex items-center gap-2">
                <a href="/billing" wire:navigate class="text-[10px] font-bold text-orange-600 hover:underline">Lihat Semua &rarr;</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
        <!-- Main Chart: Income vs Expense Trend -->
        <div class="lg:col-span-2 space-y-6 md:space-y-8">
            <div class="bg-white rounded-3xl p-4 sm:p-6 md:p-8 shadow-sm border border-slate-100">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 md:mb-8 gap-4">
                    <div>
                        <h3 class="text-lg sm:text-xl font-black text-slate-800">Tren Keuangan 7 Hari</h3>
                        <p class="text-[10px] sm:text-sm text-slate-500 font-medium">Perbandingan Pendapatan vs Pengeluaran</p>
                    </div>
                </div>
                <div class="h-[250px] sm:h-[300px] md:h-[350px]" wire:ignore>
                    <canvas id="smartDashboardChart"></canvas>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <a href="/customers" wire:navigate class="bg-blue-600 hover:bg-blue-700 p-4 rounded-2xl flex flex-col items-center justify-center gap-2 text-white transition-all hover:scale-105 shadow-lg shadow-blue-200">
                    <span class="material-symbols-outlined">person_add</span>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-center">Tambah Pelanggan</span>
                </a>
                <a href="/billing" wire:navigate class="bg-emerald-600 hover:bg-emerald-700 p-4 rounded-2xl flex flex-col items-center justify-center gap-2 text-white transition-all hover:scale-105 shadow-lg shadow-emerald-200">
                    <span class="material-symbols-outlined">point_of_sale</span>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-center">Bayar Tagihan</span>
                </a>
                <a href="/finance/expenses" wire:navigate class="bg-slate-800 hover:bg-black p-4 rounded-2xl flex flex-col items-center justify-center gap-2 text-white transition-all hover:scale-105 shadow-lg shadow-slate-200">
                    <span class="material-symbols-outlined">add_shopping_cart</span>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-center">Catat Pengeluaran</span>
                </a>
                <a href="/monitoring/status" wire:navigate class="bg-purple-600 hover:bg-purple-700 p-4 rounded-2xl flex flex-col items-center justify-center gap-2 text-white transition-all hover:scale-105 shadow-lg shadow-purple-200">
                    <span class="material-symbols-outlined">router</span>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-center">Status Router</span>
                </a>
            </div>
        </div>

        <!-- Activity Feed -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl p-4 sm:p-6 md:p-8 shadow-sm border border-slate-100 h-full">
                <h3 class="text-lg sm:text-xl font-black text-slate-800 mb-6">Aktivitas Terakhir</h3>
                <div class="space-y-5 sm:space-y-6">
                    @forelse($activities as $activity)
                        <div class="flex gap-4 group">
                            <div class="shrink-0 size-10 rounded-xl {{ $activity['type'] === 'income' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }} flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                                <span class="material-symbols-outlined text-[20px]">{{ $activity['icon'] }}</span>
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ $activity['title'] }}</p>
                                <div class="flex justify-between items-center mt-1">
                                    <p class="text-[11px] font-black {{ $activity['type'] === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $activity['type'] === 'income' ? '+' : '-' }} Rp {{ number_format($activity['amount'], 0, ',', '.') }}
                                    </p>
                                    <p class="text-[10px] font-medium text-slate-400">{{ $activity['time'] }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                            <span class="material-symbols-outlined text-4xl mb-2 opacity-20">history</span>
                            <p class="text-xs font-medium">Belum ada aktivitas</p>
                        </div>
                    @endforelse
                </div>
                
                <div class="mt-8 pt-6 border-t border-slate-50">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Router Terhubung</p>
                    <div class="space-y-3">
                        @foreach($routerStatus as $router)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="size-1.5 rounded-full {{ $router['status'] === 'online' ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                    <span class="text-xs font-bold text-slate-600">{{ $router['name'] }}</span>
                                </div>
                                <span class="text-[10px] font-black uppercase {{ $router['status'] === 'online' ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50' }} px-2 py-0.5 rounded-full">
                                    {{ $router['status'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Integration -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:navigated', () => {
            const ctx = document.getElementById('smartDashboardChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [
                        {
                            label: 'Pendapatan',
                            data: @json($incomeChartData),
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 4,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 6,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#10b981',
                            pointBorderWidth: 3
                        },
                        {
                            label: 'Pengeluaran',
                            data: @json($expenseChartData),
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.05)',
                            borderWidth: 4,
                            tension: 0.4,
                            fill: true,
                            borderDash: [5, 5],
                            pointRadius: 4,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#ef4444',
                            pointBorderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            align: 'end',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 11,
                                    weight: 'bold',
                                    family: 'Inter'
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                color: 'rgba(0,0,0,0.03)',
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    size: 10,
                                    family: 'Inter'
                                },
                                callback: function(value) {
                                    if (value >= 1000000) return (value / 1000000) + 'M';
                                    if (value >= 1000) return (value / 1000) + 'k';
                                    return value;
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 10,
                                    family: 'Inter',
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                }
            });
        });

        // First load
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('smartDashboardChart');
            if (ctx) {
                // Same chart initialization logic
                // Trigger livewire:navigated manually if needed
                window.dispatchEvent(new Event('livewire:navigated'));
            }
        });
    </script>
</div>
