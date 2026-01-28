<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Traffic Monitor</h2>
            <p class="text-sm text-slate-500">Monitoring bandwidth interface secara real-time</p>
        </div>
    </div>

    <!-- Controls -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex flex-col md:flex-row gap-4 items-end">
        <x-select label="Pilih Router" wire:model.live="router_id" :options="$routers" option-label="name" option-value="id" class="w-full md:w-64" />
        
        <x-select label="Interface" wire:model.live="interface" :options="$interfaces" option-label="name" option-value="name" class="w-full md:w-64" />
        
        <div class="flex-1"></div>
        <div class="flex items-center gap-2 text-sm text-slate-500">
             <span class="relative flex h-3 w-3">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
            </span>
            Live Update (3s)
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 gap-6">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 relative">
            <h3 class="font-bold text-slate-800 mb-4">Traffic Traffic (Bits per Second)</h3>
            
            <div class="h-[400px] w-full" wire:ignore>
                <canvas id="trafficChart"></canvas>
            </div>
            
            <!-- polling every 3 seconds -->
            <div wire:poll.3s="updateChart"></div>
        </div>
    </div>

    <!-- Legend / Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-center gap-4">
            <div class="bg-blue-500 p-3 rounded-lg text-white">
                <span class="material-symbols-outlined">download</span>
            </div>
            <div>
                <p class="text-sm text-blue-800 font-medium">Download (RX)</p>
                <p class="text-2xl font-bold text-blue-900" id="currentRx">0 Mbps</p>
            </div>
        </div>
        
        <div class="bg-green-50 border border-green-100 rounded-xl p-4 flex items-center gap-4">
            <div class="bg-green-500 p-3 rounded-lg text-white">
                <span class="material-symbols-outlined">upload</span>
            </div>
            <div>
                <p class="text-sm text-green-800 font-medium">Upload (TX)</p>
                <p class="text-2xl font-bold text-green-900" id="currentTx">0 Mbps</p>
            </div>
        </div>
    </div>

    @assets
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endassets

    @script
    <script>
        const ctx = document.getElementById('trafficChart');
        
        // Init Chart using Chart.js
        const trafficChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Download (RX)',
                    data: [],
                    borderColor: '#3b82f6', // blue-500
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Upload (TX)',
                    data: [],
                    borderColor: '#22c55e', // green-500
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 0 }, // Disable animation for smooth realtime updates
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return (value / 1000000).toFixed(1) + ' Mbps';
                            }
                        }
                    }
                },
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });

        // Listen for updates from Livewire
        Livewire.on('traffic-update', (data) => {
            const raw = data[0]; 
            // Add new data
            trafficChart.data.labels.push(raw.label);
            trafficChart.data.datasets[0].data.push(raw.rx);
            trafficChart.data.datasets[1].data.push(raw.tx);

            // Keep max 20 points
            if (trafficChart.data.labels.length > 20) {
                trafficChart.data.labels.shift();
                trafficChart.data.datasets[0].data.shift();
                trafficChart.data.datasets[1].data.shift();
            }

            trafficChart.update();

            // Update Text Stats
            document.getElementById('currentRx').innerText = (raw.rx / 1000000).toFixed(2) + ' Mbps';
            document.getElementById('currentTx').innerText = (raw.tx / 1000000).toFixed(2) + ' Mbps';
        });
    </script>
    @endscript
</div>
