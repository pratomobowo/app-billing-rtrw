<div class="h-[calc(100vh-8rem)] flex flex-col">
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-slate-800">Peta Sebaran</h2>
        <p class="text-sm text-slate-500">Lokasi Pelanggan dan ODP</p>
    </div>

    <div class="flex-1 bg-white rounded-xl border border-slate-200 shadow-sm relative overflow-hidden z-0">
        <div id="map" class="absolute inset-0 z-0" 
             x-init="
                if (window.leafletMap) { window.leafletMap.remove(); }
                window.leafletMap = L.map($el).setView([-6.200000, 106.816666], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(window.leafletMap);

                var customerIcon = L.divIcon({
                    html: '<span class=\"material-symbols-outlined text-blue-600 text-3xl drop-shadow-md\">person_pin_circle</span>',
                    className: 'bg-transparent',
                    iconSize: [30, 30],
                    iconAnchor: [15, 30],
                    popupAnchor: [0, -30]
                });

                var odpIcon = L.divIcon({
                    html: '<span class=\"material-symbols-outlined text-red-600 text-3xl drop-shadow-md\">router</span>',
                    className: 'bg-transparent',
                    iconSize: [30, 30],
                    iconAnchor: [15, 30],
                    popupAnchor: [0, -30]
                });

                var customers = @js($customers);
                var odps = @js($odps);
                var bounds = L.latLngBounds();

                customers.forEach(c => {
                    var marker = L.marker([c.latitude, c.longitude], {icon: customerIcon}).addTo(window.leafletMap);
                    marker.bindPopup(`
                        <div class=\"p-2 min-w-[200px]\">
                            <h3 class=\"font-bold text-slate-800\">${c.name}</h3>
                            <p class=\"text-xs text-slate-500 mb-1\">${c.address ?? '-'}</p>
                            <div class=\"flex items-center gap-2 mt-2\">
                                <span class=\"px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 uppercase\">${c.status}</span>
                                <a href=\"https://wa.me/${c.whatsapp}\" target=\"_blank\" class=\"text-green-600 hover:underline text-xs flex items-center gap-1\">
                                    <span class=\"material-symbols-outlined text-[14px]\">chat</span> Chat
                                </a>
                            </div>
                        </div>
                    `);
                    bounds.extend(marker.getLatLng());
                });

                odps.forEach(o => {
                    var marker = L.marker([o.latitude, o.longitude], {icon: odpIcon}).addTo(window.leafletMap);
                    marker.bindPopup(`
                        <div class=\"p-2 min-w-[200px]\">
                            <h3 class=\"font-bold text-slate-800\">${o.name}</h3>
                            <p class=\"text-xs text-slate-500 mb-2\">${o.description ?? '-'}</p>
                            <div class=\"bg-slate-100 rounded-lg p-2 text-center\">
                                <p class=\"text-[10px] text-slate-500 uppercase font-bold\">Kapasitas</p>
                                <p class=\"font-mono font-bold text-lg\">${o.filled} <span class=\"text-slate-400 text-sm\">/ ${o.capacity}</span></p>
                            </div>
                        </div>
                    `);
                    bounds.extend(marker.getLatLng());
                });

                if (customers.length > 0 || odps.length > 0) {
                    window.leafletMap.fitBounds(bounds, {padding: [50, 50]});
                }
             "
        ></div>
    </div>
</div>
