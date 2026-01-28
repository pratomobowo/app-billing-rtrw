<div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-100">
    <div class="p-8">
        <div class="flex flex-col items-center mb-6">
            <div class="bg-primary/10 p-4 rounded-full mb-4">
                @if(isset($appSettings['app_logo']))
                    <img src="{{ $appSettings['app_logo'] }}" class="h-10 w-10 object-contain">
                @else
                    <span class="material-symbols-outlined text-primary text-4xl">router</span>
                @endif
            </div>
            <h2 class="text-2xl font-bold text-slate-800">{{ $appSettings['app_name'] ?? 'Billing Login' }}</h2>
            <p class="text-slate-500 text-sm">Masuk untuk mengelola sistem</p>
        </div>

        <form wire:submit="login" class="space-y-4">
            <x-input label="Email Address" wire:model="email" icon="o-envelope" placeholder="email@example.com" />
            <x-input label="Password" wire:model="password" type="password" icon="o-key" placeholder="••••••••" />
            
            <div class="flex justify-between items-center text-sm">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model="remember" class="checkbox checkbox-primary checkbox-xs" />
                    <span class="text-slate-600">Remember me</span>
                </label>
            </div>

            <x-button label="Masuk" type="submit" class="btn-primary w-full shadow-lg shadow-primary/20" spinner="login" />
        </form>
    </div>
    <div class="bg-slate-50 p-4 text-center border-t border-slate-100">
        <p class="text-xs text-slate-400">&copy; {{ date('Y') }} RT RW Net Manager v1.0</p>
    </div>
</div>
