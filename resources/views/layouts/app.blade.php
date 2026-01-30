<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>
<body class="bg-background-light text-slate-800 h-screen overflow-hidden flex font-sans antialiased" x-data="{ sidebarOpen: false }">
    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false" 
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 lg:hidden"></div>

    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 w-64 bg-sidebar-dark h-full flex flex-col shrink-0 transition-transform duration-300 z-40 lg:relative lg:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <!-- Sidebar Header -->
        <div class="h-20 flex items-center justify-between px-6 border-b border-primary-600/30 mb-4">
            <div class="flex items-center gap-3">
                <div class="bg-white/10 p-2 rounded-lg backdrop-blur-sm">
                    @if(isset($appSettings['app_logo']))
                        <img src="{{ $appSettings['app_logo'] }}" class="h-8 w-8 object-contain">
                    @else
                        <span class="material-symbols-outlined text-white text-2xl">router</span>
                    @endif
                </div>
                <div>
                    <h1 class="font-bold text-lg tracking-wide text-white">{{ $appSettings['app_name'] ?? 'Billing App' }}</h1>
                    <p class="text-[10px] text-white/70 uppercase tracking-widest">{{ $appSettings['company_name'] ?? 'Networking' }}</p>
                </div>
            </div>
            <!-- Close button for mobile -->
            <button @click="sidebarOpen = false" class="lg:hidden text-white/70 hover:text-white">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-6 px-3 flex flex-col gap-1">
            <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Main Menu</p>
            
            <!-- Dashboard -->
            <a href="/" wire:navigate class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg text-slate-300 hover:text-white group {{ request()->is('/') ? 'active' : '' }}">
                <span class="material-symbols-outlined text-[22px]">dashboard</span>
                <span class="text-sm font-medium">Dashboard</span>
            </a>

            <!-- Pelanggan -->
            <a href="/customers" wire:navigate class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg text-slate-300 hover:text-white group {{ request()->is('customers*') ? 'active' : '' }}">
                <span class="material-symbols-outlined text-[22px]">group</span>
                <span class="text-sm font-medium">Pelanggan</span>
            </a>

            <!-- Layanan Dropdown -->
            <div x-data="{ open: {{ request()->is('packages*', 'routers*', 'radius*', 'monitoring/traffic*', 'monitoring/logs*', 'network/map*', 'network/olt*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full sidebar-link flex items-center justify-between px-3 py-3 rounded-lg text-slate-300 hover:text-white group transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[22px] group-hover:text-white transition-colors">wifi_tethering</span>
                        <span class="text-sm font-medium">Layanan</span>
                    </div>
                    <span class="material-symbols-outlined text-[20px] transition-transform duration-200" :class="open ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="open" x-collapse class="pl-4 flex flex-col mt-1 space-y-1">
                    <a href="/packages" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:text-white text-sm {{ request()->is('packages*') ? 'text-white bg-white/5' : '' }}">
                        <span class="material-symbols-outlined text-[18px]">wifi</span> Paket Internet
                    </a>
                    <a href="/routers" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:text-white text-sm {{ request()->is('routers*') ? 'text-white bg-white/5' : '' }}">
                        <span class="material-symbols-outlined text-[18px]">router</span> Router Mikrotik
                    </a>
                    <a href="/radius" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:text-white text-sm {{ request()->is('radius*') ? 'text-white bg-white/5' : '' }}">
                        <span class="material-symbols-outlined text-[18px]">hub</span> Radius Monitor
                    </a>
                    <a href="/monitoring/traffic" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:text-white text-sm {{ request()->is('monitoring/traffic*') ? 'text-white bg-white/5' : '' }}">
                        <span class="material-symbols-outlined text-[18px]">ssid_chart</span> Traffic Monitor
                    </a>
                    <a href="/monitoring/logs" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:text-white text-sm {{ request()->is('monitoring/logs*') ? 'text-white bg-white/5' : '' }}">
                        <span class="material-symbols-outlined text-[18px]">history</span> Log Mikrotik
                    </a>
                    <a href="/network/map" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:text-white text-sm {{ request()->is('network/map*') ? 'text-white bg-white/5' : '' }}">
                        <span class="material-symbols-outlined text-[18px]">map</span> Peta Sebaran
                    </a>
                    <a href="/network/olt" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:text-white text-sm {{ request()->is('network/olt*') ? 'text-white bg-white/5' : '' }}">
                        <span class="material-symbols-outlined text-[18px]">settings_input_component</span> OLT Management
                    </a>
                </div>
            </div>

            <!-- Hotspot Dropdown -->
            <div x-data="{ open: {{ request()->is('hotspot*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full sidebar-link flex items-center justify-between px-3 py-3 rounded-lg text-slate-300 hover:text-white group transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[22px] group-hover:text-white transition-colors">magic_button</span>
                        <span class="text-sm font-medium">Hotspot</span>
                    </div>
                    <span class="material-symbols-outlined text-[20px] transition-transform duration-200" :class="open ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="open" x-collapse class="pl-4 flex flex-col mt-1 space-y-1">
                    <a href="/hotspot/vouchers" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:text-white text-sm {{ request()->is('hotspot/vouchers*') ? 'text-white bg-white/5' : '' }}">
                        <span class="material-symbols-outlined text-[18px]">confirmation_number</span> Voucher Hotspot
                    </a>
                    <a href="/hotspot/profiles" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:text-white text-sm {{ request()->is('hotspot/profiles*') ? 'text-white bg-white/5' : '' }}">
                        <span class="material-symbols-outlined text-[18px]">identity_platform</span> Profil Voucher
                    </a>
                </div>
            </div>

            <!-- Keuangan Dropdown -->
            <div x-data="{ open: {{ request()->is('billing*', 'invoices*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full sidebar-link flex items-center justify-between px-3 py-3 rounded-lg text-slate-300 hover:text-white group transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[22px] group-hover:text-white transition-colors">payments</span>
                        <span class="text-sm font-medium">Keuangan</span>
                    </div>
                    <span class="material-symbols-outlined text-[20px] transition-transform duration-200" :class="open ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="open" x-collapse class="pl-4 flex flex-col mt-1 space-y-1">
                    <a href="/billing" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:text-white text-sm {{ request()->is('billing*') ? 'text-white bg-white/5' : '' }}">
                        <span class="material-symbols-outlined text-[18px]">account_balance_wallet</span> Tagihan (Billing)
                    </a>
                    <a href="/invoices" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:text-white text-sm {{ request()->is('invoices*') ? 'text-white bg-white/5' : '' }}">
                        <span class="material-symbols-outlined text-[18px]">receipt_long</span> Riwayat Invoice
                    </a>
                </div>
            </div>

            <!-- WhatsApp Dropdown -->
            <div x-data="{ open: {{ request()->is('whatsapp*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full sidebar-link flex items-center justify-between px-3 py-3 rounded-lg text-slate-300 hover:text-white group transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[22px] group-hover:text-white transition-colors">chat</span>
                        <span class="text-sm font-medium">WhatsApp</span>
                    </div>
                    <span class="material-symbols-outlined text-[20px] transition-transform duration-200" :class="open ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="open" x-collapse class="pl-4 flex flex-col mt-1 space-y-1">
                    <a href="/whatsapp" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:text-white text-sm {{ request()->is('whatsapp') ? 'text-white bg-white/5' : '' }}">
                        <span class="material-symbols-outlined text-[18px]">devices</span> Device Manager
                    </a>
                    <a href="/whatsapp/broadcast" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:text-white text-sm {{ request()->is('whatsapp/broadcast*') ? 'text-white bg-white/5' : '' }}">
                        <span class="material-symbols-outlined text-[18px]">campaign</span> Broadcast
                    </a>
                </div>
            </div>

            <!-- Sistem Dropdown -->
            <div x-data="{ open: {{ request()->is('users*', 'settings*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full sidebar-link flex items-center justify-between px-3 py-3 rounded-lg text-slate-300 hover:text-white group transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[22px] group-hover:text-white transition-colors">settings_suggest</span>
                        <span class="text-sm font-medium">Sistem</span>
                    </div>
                    <span class="material-symbols-outlined text-[20px] transition-transform duration-200" :class="open ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="open" x-collapse class="pl-4 flex flex-col mt-1 space-y-1">
                    <a href="/users" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:text-white text-sm {{ request()->is('users*') ? 'text-white bg-white/5' : '' }}">
                        <span class="material-symbols-outlined text-[18px]">manage_accounts</span> Manajemen User
                    </a>
                    <a href="/settings" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:text-white text-sm {{ request()->is('settings*') && !request()->is('settings/docs*') ? 'text-white bg-white/5' : '' }}">
                        <span class="material-symbols-outlined text-[18px]">settings</span> Konfigurasi
                    </a>
                    <a href="/settings/docs" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:text-white text-sm {{ request()->is('settings/docs*') ? 'text-white bg-white/5' : '' }}">
                        <span class="material-symbols-outlined text-[18px]">menu_book</span> Dokumentasi
                    </a>
                </div>
            </div>
        </nav>

        <!-- User Profile (Bottom) -->
        <div class="p-4 border-t border-white/10">
            @if($user = auth()->user())
                <div class="flex items-center justify-between gap-2 p-2 rounded-lg bg-white/5 hover:bg-white/10 transition-colors group">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="size-9 shrink-0 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="flex flex-col overflow-hidden">
                            <p class="text-white text-sm font-medium truncate group-hover:text-blue-200 transition-colors">{{ $user->name }}</p>
                            <p class="text-slate-400 text-xs truncate">Administrator</p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-red-400 hover:bg-white/5 rounded-lg transition-all" title="Logout">
                            <span class="material-symbols-outlined text-[20px]">logout</span>
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative w-full">
        <!-- Top Header -->
        <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-4 md:px-8 shrink-0 z-10 sticky top-0">
            <div class="flex items-center gap-4">
                <!-- Hamburger Button -->
                <button @click="sidebarOpen = true" class="p-2 -ml-2 text-slate-500 hover:text-primary lg:hidden rounded-lg hover:bg-slate-100 transition-colors">
                    <span class="material-symbols-outlined">menu</span>
                </button>

                @if(($title ?? 'Dashboard') === 'Dashboard')
                    <div class="flex flex-col justify-center">
                        <h2 class="text-lg md:text-xl font-bold text-slate-800 leading-tight truncate max-w-[150px] md:max-w-none">{{ $appSettings['company_name'] ?? 'Billing System' }}</h2>
                        <p class="text-[10px] md:text-xs text-slate-500 truncate max-w-[150px] md:max-w-none">{{ $appSettings['company_address'] ?? '' }}</p>
                    </div>
                @else
                    <h2 class="text-lg md:text-xl font-bold text-slate-800 truncate">{{ $title }}</h2>
                @endif
            </div>

            <div class="flex items-center gap-2 md:gap-4">
                <div class="relative hidden xl:block">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                    <input class="pl-10 pr-4 py-2 w-64 rounded-lg bg-slate-100 border-none text-sm focus:ring-2 focus:ring-primary text-slate-700 placeholder-slate-400 transition-all" placeholder="Cari data..." type="text" />
                </div>
                <livewire:components.header-notification />
            </div>
        </header>

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto p-4 md:p-8">
            <div class="max-w-[1200px] mx-auto">
                {{ $slot }}
            </div>
        </div>
    </main>

    <x-toast />
</body>
</html>
