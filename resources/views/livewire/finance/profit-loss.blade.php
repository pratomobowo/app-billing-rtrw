<div>
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Laporan Laba Rugi</h2>
            <p class="text-slate-500 text-sm mt-1">Pantau performa finansial bisnis RTRW Net Anda.</p>
        </div>
        
        <div class="flex items-center gap-2">
            <x-select wire:model.live="month" :options="[
                ['id' => '01', 'name' => 'Januari'], ['id' => '02', 'name' => 'Februari'], ['id' => '03', 'name' => 'Maret'],
                ['id' => '04', 'name' => 'April'], ['id' => '05', 'name' => 'Mei'], ['id' => '06', 'name' => 'Juni'],
                ['id' => '07', 'name' => 'Juli'], ['id' => '08', 'name' => 'Agustus'], ['id' => '09', 'name' => 'September'],
                ['id' => '10', 'name' => 'Oktober'], ['id' => '11', 'name' => 'November'], ['id' => '12', 'name' => 'Desember'],
            ]" placeholder="Pilih Bulan" class="w-40" />
            <x-select wire:model.live="year" :options="[
                ['id' => 2025, 'name' => '2025'], ['id' => 2026, 'name' => '2026'], ['id' => 2027, 'name' => '2027']
            ]" placeholder="Pilih Tahun" class="w-32" />
        </div>
    </div>

    <!-- Financial Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Income Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:rotate-12 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-6xl text-green-600">trending_up</span>
            </div>
            <p class="text-slate-500 text-sm font-medium uppercase tracking-wider">Total Pemasukan</p>
            <h3 class="text-3xl font-bold text-slate-800 mt-2">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
            <p class="text-xs text-green-600 font-medium mt-1 flex items-center gap-1">
                <span class="material-symbols-outlined text-xs">info</span> Berdasarkan invoice lunas
            </p>
        </div>

        <!-- Expense Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:rotate-12 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-6xl text-red-600">trending_down</span>
            </div>
            <p class="text-slate-500 text-sm font-medium uppercase tracking-wider">Total Pengeluaran</p>
            <h3 class="text-3xl font-bold text-slate-800 mt-2">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
            <p class="text-xs text-red-500 font-medium mt-1 flex items-center gap-1">
                <span class="material-symbols-outlined text-xs">info</span> Berdasarkan catatan biaya
            </p>
        </div>

        <!-- Net Profit Card -->
        <div class="{{ $netProfit >= 0 ? 'bg-primary-600 text-white' : 'bg-red-600 text-white' }} rounded-2xl p-6 shadow-lg shadow-black/5 relative overflow-hidden">
            <div class="absolute right-0 top-0 p-4 opacity-20">
                <span class="material-symbols-outlined text-6xl">account_balance_wallet</span>
            </div>
            <p class="{{ $netProfit >= 0 ? 'text-primary-100' : 'text-red-100' }} text-sm font-medium uppercase tracking-wider">Laba Bersih (Estimasi)</p>
            <h3 class="text-3xl font-bold mt-2">Rp {{ number_format($netProfit, 0, ',', '.') }}</h3>
            <p class="text-xs {{ $netProfit >= 0 ? 'text-primary-200' : 'text-red-200' }} mt-1">Sisa saldo setelah operasional</p>
        </div>
    </div>

    <!-- Trend Chart -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-slate-800 text-lg">Tren Keuangan (6 Bulan Terakhir)</h3>
            <div class="flex items-center gap-4 text-xs font-medium">
                <div class="flex items-center gap-1.5">
                    <span class="size-3 rounded-full bg-primary-500"></span> Pemasukan
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="size-3 rounded-full bg-red-400"></span> Pengeluaran
                </div>
            </div>
        </div>
        
        <div class="h-[350px] w-full" wire:ignore>
            <canvas id="financeChart"></canvas>
        </div>
    </div>

    @assets
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endassets

    @script
    <script>
        const ctx = document.getElementById('financeChart').getContext('2d');
        let financeChart;

        const initChart = (data) => {
            if (financeChart) {
                financeChart.destroy();
            }

            financeChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(d => d.label),
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: data.map(d => d.income),
                            backgroundColor: '#4f46e5', // primary-600
                            borderRadius: 6,
                            borderSkipped: false,
                        },
                        {
                            label: 'Pengeluaran',
                            data: data.map(d => d.expense),
                            backgroundColor: '#f87171', // red-400
                            borderRadius: 6,
                            borderSkipped: false,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                color: '#f1f5f9'
                            },
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) return 'Rp ' + (value / 1000000) + 'jt';
                                    if (value >= 1000) return 'Rp ' + (value / 1000) + 'rb';
                                    return 'Rp ' + value;
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        };

        // Initial load
        initChart($wire.monthlyData);

        // Listen for Livewire updates
        $wire.on('stats-updated', (event) => {
            initChart(event.data);
        });
    </script>
    @endscript
</div>
