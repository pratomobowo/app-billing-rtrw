<div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Pengaturan Aplikasi</h2>
            <p class="text-sm text-slate-500">Sesuaikan identitas aplikasi Anda</p>
        </div>
        <div>
             <a href="/settings" wire:navigate class="btn btn-ghost btn-sm">
                <span class="material-symbols-outlined mr-2">arrow_back</span> Kembali
             </a>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 max-w-2xl">
        <form wire:submit="save" class="space-y-6">
            
            <!-- Logo Section -->
            <div class="flex items-center gap-6">
                <div class="shrink-0 relative group">
                    @if($current_logo || $logo)
                        <img src="{{ $logo ? $logo->temporaryUrl() : $current_logo }}" class="size-24 rounded-xl object-cover border border-slate-200 bg-slate-50" />
                    @else
                        <div class="size-24 rounded-xl bg-slate-100 flex items-center justify-center border border-slate-200 text-slate-400">
                            <span class="material-symbols-outlined text-4xl">image</span>
                        </div>
                    @endif
                    
                    <label class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-xl cursor-pointer text-white">
                        <span class="material-symbols-outlined">upload</span>
                        <input type="file" wire:model="logo" class="hidden" accept="image/*">
                    </label>
                </div>
                <div>
                     <h3 class="font-bold text-slate-800">Logo Aplikasi</h3>
                     <p class="text-xs text-slate-500 mb-2">Format: PNG/JPG, Max 1MB. Disarankan rasio 1:1.</p>
                     @error('logo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="divider"></div>

            <div class="grid grid-cols-1 gap-4">
                <x-input label="Nama Aplikasi" wire:model="app_name" placeholder="Contoh: Vibe Billing" />
                <x-input label="Nama Perusahaan" wire:model="company_name" placeholder="Contoh: Vibe Networks" />
                <x-textarea label="Alamat Perusahaan" wire:model="company_address" rows="3" />
                <x-input label="Nomor Telepon / WA Support" wire:model="company_phone" placeholder="0812..." />
            </div>

            <div class="flex justify-end pt-4">
                <x-button label="Simpan Perubahan" class="btn-primary" type="submit" spinner="save" />
            </div>
        </form>
    </div>
</div>
