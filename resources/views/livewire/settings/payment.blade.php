<div>
    <div class="flex items-center gap-4 mb-6">
        <a href="/settings" wire:navigate class="btn btn-sm btn-circle btn-ghost">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Payment Gateway</h2>
            <p class="text-sm text-slate-500">Kelola metode pembayaran dan integrasi API</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6">
        @foreach($gateways as $gateway)
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="bg-white p-2 rounded-lg border border-slate-200">
                           @if($gateway->code == 'midtrans')
                                <span class="font-bold text-blue-800 text-xs">MIDTRANS</span>
                           @elseif($gateway->code == 'xendit')
                                <span class="font-bold text-slate-800 text-xs">XENDIT</span>
                           @else
                                <span class="material-symbols-outlined text-green-600">payments</span>
                           @endif
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">{{ $gateway->name }}</h3>
                            <span class="text-xs text-slate-500 px-2 py-0.5 rounded-full {{ $gateway->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $gateway->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <x-toggle wire:click="toggle({{ $gateway->id }})" wire:model="gateways.{{ $loop->index }}.is_active" class="toggle-primary" />
                </div>
                
                <div class="p-6 grid gap-4">
                    @if($gateway->code == 'manual')
                        <x-textarea label="Instruksi Transfer" wire:model="configs.{{ $gateway->id }}.instruction" rows="3" hint="Tuliskan nomor rekening dan atas nama" />
                    @elseif($gateway->code == 'midtrans')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-input label="Server Key" wire:model="configs.{{ $gateway->id }}.server_key" />
                            <x-input label="Client Key" wire:model="configs.{{ $gateway->id }}.client_key" />
                        </div>
                        <x-toggle label="Production Mode" wire:model="configs.{{ $gateway->id }}.is_production" />
                    @elseif($gateway->code == 'xendit')
                        <div class="grid grid-cols-1 gap-4">
                            <x-input label="API Key" wire:model="configs.{{ $gateway->id }}.api_key" type="password" />
                            <x-input label="Callback Token" wire:model="configs.{{ $gateway->id }}.callback_token" />
                        </div>
                    @endif

                    <div class="flex justify-end mt-2">
                        <x-button label="Simpan Konfigurasi" class="btn-primary btn-sm" wire:click="save({{ $gateway->id }})" spinner="save({{ $gateway->id }})" />
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
