<div class="h-[calc(100vh-8rem)] flex flex-col">
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-slate-800">Peta Sebaran</h2>
        <p class="text-sm text-slate-500">Lokasi Pelanggan dan ODP</p>
    </div>

    <div class="flex-1 bg-white rounded-xl border border-slate-200 shadow-sm relative overflow-hidden z-0">
        <div id="map" class="absolute inset-0 z-0"></div>
    </div>

    <!-- Leaflet CSS & JS -->
    @assets
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <style>
        .leaflet-popup-content-wrapper { border-radius: 12px; }
    </style>
    @endassets

    @script
    <script>
        // Init Map
        const map = L.map('map').setView([-6.200000, 106.816666], 13); // Default Jakarta

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Icons
        const customerIcon = L.divIcon({
            html: '<span class="material-symbols-outlined text-blue-600 text-3xl drop-shadow-md">person_pin_circle</span>',
            className: 'bg-transparent',
            iconSize: [30, 30],
            iconAnchor: [15, 30],
            popupAnchor: [0, -30]
        });

        const odpIcon = L.divIcon({
            html: '<span class="material-symbols-outlined text-red-600 text-3xl drop-shadow-md">router</span>',
            className: 'bg-transparent',
            iconSize: [30, 30],
            iconAnchor: [15, 30],
            popupAnchor: [0, -30]
        });

        // Customers
        const customers = @json($customers);
        const odps = @json($odps);
        const bounds = L.latLngBounds();

        customers.forEach(c => {
            const marker = L.marker([c.latitude, c.longitude], {icon: customerIcon}).addTo(map);
            marker.bindPopup(`
                <div class="p-2 min-w-[200px]">
                    <h3 class="font-bold text-slate-800">${c.name}</h3>
                    <p class="text-xs text-slate-500 mb-1">${c.address ?? '-'}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 uppercase">${c.status}</span>
                        <a href="https://wa.me/${c.whatsapp}" target="_blank" class="text-green-600 hover:underline text-xs flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">chat</span> Chat
                        </a>
                    </div>
                </div>
            `);
            bounds.extend(marker.getLatLng());
        });

        // ODPs
        odps.forEach(o => {
            const marker = L.marker([o.latitude, o.longitude], {icon: odpIcon}).addTo(map);
            marker.bindPopup(`
                <div class="p-2 min-w-[200px]">
                    <h3 class="font-bold text-slate-800">${o.name}</h3>
                    <p class="text-xs text-slate-500 mb-2">${o.description ?? '-'}</p>
                    <div class="bg-slate-100 rounded-lg p-2 text-center">
                        <p class="text-[10px] text-slate-500 uppercase font-bold">Kapasitas</p>
                        <p class="font-mono font-bold text-lg">${o.filled} <span class="text-slate-400 text-sm">/ ${o.capacity}</span></p>
                    </div>
                </div>
            `);
            bounds.extend(marker.getLatLng());
        });

        // Auto Zoom
        if (customers.length > 0 || odps.length > 0) {
            map.fitBounds(bounds, {padding: [50, 50]});
        }
    </script>
    @endscript
</div>
