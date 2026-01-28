<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    <button @click="open = !open" class="size-10 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-100 relative transition-colors">
        <span class="material-symbols-outlined {{ $count > 0 ? 'animate-pulse text-primary' : '' }}">notifications</span>
        @if($count > 0)
            <span class="absolute top-2 right-2 size-2 bg-red-500 rounded-full border border-white"></span>
        @endif
    </button>

    <div x-show="open" x-transition 
         class="absolute right-0 top-12 w-80 bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden z-50">
        
        <div class="p-3 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="font-bold text-sm text-slate-700">Notifikasi ({{ $count }})</h3>
            @if($count > 0)
                <button wire:click="markAllRead" class="text-[10px] text-blue-600 hover:underline font-medium">Tandai Baca Semua</button>
            @endif
        </div>

        <div class="max-h-64 overflow-y-auto">
            @forelse($notifications as $notif)
                <div class="p-3 border-b border-slate-50 hover:bg-slate-50 transition-colors flex gap-3 cursor-pointer" wire:click="markAsRead('{{ $notif->id }}')">
                    <div class="shrink-0 mt-1">
                        @if($notif->data['type'] === 'danger')
                            <span class="material-symbols-outlined text-red-500 bg-red-100 p-1 rounded-full text-[18px]">error</span>
                        @elseif($notif->data['type'] === 'warning')
                            <span class="material-symbols-outlined text-yellow-500 bg-yellow-100 p-1 rounded-full text-[18px]">warning</span>
                        @else
                            <span class="material-symbols-outlined text-blue-500 bg-blue-100 p-1 rounded-full text-[18px]">info</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-800">{{ $notif->data['title'] }}</p>
                        <p class="text-xs text-slate-500 mt-0.5 line-clamp-2">{{ $notif->data['message'] }}</p>
                        <p class="text-[10px] text-slate-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400">
                    <span class="material-symbols-outlined text-3xl mb-2 opacity-50">notifications_off</span>
                    <p class="text-xs">Tidak ada notifikasi baru</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
