<div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">WhatsApp Gateway</h2>
            <p class="text-sm text-slate-500">Kelola device WhatsApp untuk notifikasi otomatis</p>
        </div>
        <div>
            <x-button label="Tambah Device" icon="o-plus" class="btn-primary shadow-lg shadow-primary/20" @click="$wire.deviceModal = true" />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Device List -->
        @foreach($devices as $device)
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-green-50 p-3 rounded-full">
                             <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" class="size-6" alt="WA">
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">{{ $device->device_id }}</h3>
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $device->status == 'authenticated' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $device->status }}
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <x-button icon="o-arrow-path" class="btn-sm btn-circle btn-ghost" tooltip="Cek Status" wire:click="checkStatus({{ $device->id }})" spinner="checkStatus({{ $device->id }})" />
                        <x-button icon="o-trash" class="btn-sm btn-circle btn-ghost text-red-500" tooltip="Hapus" wire:click="delete({{ $device->id }})" wire:confirm="Hapus device ini?" />
                    </div>
                </div>

                <div class="space-y-3">
                     @if($qrCodeUrl && $loop->iteration == 1) 
                        <!-- Show QR only if requested, simplistic logic for now -->
                        <div class="flex justify-center bg-slate-100 p-4 rounded-lg">
                            <img src="{{ $qrCodeUrl }}" class="size-48" alt="Scan QR">
                        </div>
                        <p class="text-center text-xs text-slate-500">Scan QR Code ini menggunakan WhatsApp di HP Anda</p>
                     @else
                        <x-button label="Sambungkan / Scan QR" class="btn-outline btn-sm w-full" wire:click="getQr({{ $device->id }})" spinner="getQr({{ $device->id }})" />
                     @endif
                </div>

                <!-- Test Sender -->
                <div class="mt-6 pt-4 border-t border-slate-100">
                    <p class="text-xs font-bold text-slate-500 uppercase mb-3">Test Kirim Pesan</p>
                    <div class="flex gap-2">
                         <x-input wire:model="test_phone" placeholder="0812..." class="flex-1 input-sm" />
                         <x-button icon="o-paper-airplane" class="btn-primary btn-sm" wire:click="sendTest({{ $device->id }})" spinner="sendTest({{ $device->id }})" />
                    </div>
                </div>
            </div>
        @endforeach

        @if($devices->isEmpty())
             <div class="col-span-1 md:col-span-2 text-center py-12 text-slate-400">
                <span class="material-symbols-outlined text-4xl mb-2">phonelink_off</span>
                <p>Belum ada device terhubung.</p>
             </div>
        @endif
    </div>

    <!-- Modal Add Device -->
    <x-modal wire:model="deviceModal" title="Tambah WhatsApp Device" separator>
        <div class="grid gap-4">
            <x-input label="Device ID (Unik)" wire:model="device_id" placeholder="my-wa-bot" hint="ID untuk identifikasi device ini" />
            <x-input label="API Key (Optional)" wire:model="api_key" placeholder="security-key" />
        </div>
        <x-slot:actions>
            <x-button label="Batal" @click="$wire.deviceModal = false" />
            <x-button label="Simpan" class="btn-primary" wire:click="createDevice" spinner="createDevice" />
        </x-slot:actions>
    </x-modal>
</div>
