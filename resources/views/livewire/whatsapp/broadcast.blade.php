<div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Broadcast Pesan</h2>
            <p class="text-sm text-slate-500">Kirim pesan massal ke pelanggan</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Configuration -->
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">tune</span> Konfigurasi
                </h3>
                
                <div class="space-y-4">
                    <x-select label="Pilih Device Pengirim" wire:model="deviceId" :options="$devices" option-label="device_id" option-value="id" />
                    
                    <x-select label="Target Penerima" wire:model.live="target" :options="[
                        ['id' => 'unpaid', 'name' => 'Tagihan Belum Lunas'],
                        ['id' => 'all_customers', 'name' => 'Semua Pelanggan Aktif'],
                        ['id' => 'router', 'name' => 'Pelanggan per Router'],
                    ]" option-label="name" option-value="id" />

                    @if($target === 'router')
                        <x-select label="Pilih Router" wire:model.live="router_id" :options="$routers" option-label="name" option-value="id" placeholder="-- Pilih Router --" />
                    @endif
                    
                    <div class="bg-blue-50 p-4 rounded-lg flex items-center justify-between">
                         <span class="text-sm text-slate-600 font-medium">Estimasi Penerima:</span>
                         <span class="text-lg font-bold text-blue-700">{{ $recipientCount }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                 <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">style</span> Template Cepat
                </h3>
                <div class="flex flex-col gap-2">
                    <x-button label="Tagihan Bulanan" class="btn-outline btn-sm justify-start" wire:click="setTemplate('tagihan')" icon="o-document-text" />
                    <x-button label="Info Gangguan" class="btn-outline btn-sm justify-start" wire:click="setTemplate('gangguan')" icon="o-signal-slash" />
                    <x-button label="Info Umum" class="btn-outline btn-sm justify-start" wire:click="setTemplate('info')" icon="o-information-circle" />
                </div>
            </div>
        </div>

        <!-- Message Composer -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 h-full flex flex-col">
                 <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">edit_note</span> Isi Pesan
                </h3>
                
                <x-textarea wire:model="message" rows="12" class="flex-1 font-mono text-sm leading-relaxed" placeholder="Tulis pesan anda disini..." />
                
                <div class="mt-4 p-4 bg-slate-50 rounded-lg text-xs text-slate-500 space-y-2">
                    <p class="font-bold">Variabel yang tersedia:</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-2 py-1 bg-white border border-slate-200 rounded text-slate-700 font-mono">{name}</span>
                        <span class="px-2 py-1 bg-white border border-slate-200 rounded text-slate-700 font-mono">{address}</span>
                        @if($target === 'unpaid')
                            <span class="px-2 py-1 bg-white border border-slate-200 rounded text-slate-700 font-mono">{tagihan}</span>
                            <span class="px-2 py-1 bg-white border border-slate-200 rounded text-slate-700 font-mono">{bulan}</span>
                            <span class="px-2 py-1 bg-white border border-slate-200 rounded text-slate-700 font-mono">{duedate}</span>
                        @endif
                    </div>
                </div>

                <div class="mt-6 flex justify-end items-center gap-4">
                     <span wire:loading target="send" class="text-sm text-slate-500 animate-pulse">Sedang mengirim...</span>
                     <x-button label="Kirim Broadcast" class="btn-primary" wire:click="send" spinner="send" icon="o-paper-airplane"
                        wire:confirm="Yakin ingin mengirim pesan ke {{ $recipientCount }} penerima?" />
                </div>
            </div>
        </div>
    </div>
</div>
