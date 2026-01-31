<div>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Manajemen Infrastruktur</h2>
            <p class="text-slate-500 font-medium">Kelola OLT, ODC, dan ODP dalam satu tempat.</p>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="size-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <span class="material-symbols-outlined">settings_input_component</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total OLT</p>
                <h4 class="text-xl font-black text-slate-800">{{ $oltCount }} Unit</h4>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="size-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <span class="material-symbols-outlined">account_tree</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total ODC</p>
                <h4 class="text-xl font-black text-slate-800">{{ $odcCount }} Unit</h4>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="size-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <span class="material-symbols-outlined">hub</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total ODP</p>
                <h4 class="text-xl font-black text-slate-800">{{ $odpCount }} Unit</h4>
            </div>
        </div>
    </div>

    <!-- Main Content Tabs -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="flex border-b border-slate-100 px-4 md:px-8">
            <button wire:click="$set('activeTab', 'olt')" class="px-6 py-5 text-sm font-bold transition-all border-b-2 {{ $activeTab === 'olt' ? 'border-primary text-primary' : 'border-transparent text-slate-400' }}">
                OLT FTTH
            </button>
            <button wire:click="$set('activeTab', 'odc')" class="px-6 py-5 text-sm font-bold transition-all border-b-2 {{ $activeTab === 'odc' ? 'border-primary text-primary' : 'border-transparent text-slate-400' }}">
                ODC (Distribution Center)
            </button>
            <button wire:click="$set('activeTab', 'odp')" class="px-6 py-5 text-sm font-bold transition-all border-b-2 {{ $activeTab === 'odp' ? 'border-primary text-primary' : 'border-transparent text-slate-400' }}">
                ODP (Distribution Point)
            </button>
        </div>

        <div class="p-6 md:p-8">
            @if($activeTab === 'olt')
                <livewire:network.olt.index />
            @elseif($activeTab === 'odc')
                <livewire:network.odc-index />
            @elseif($activeTab === 'odp')
                <livewire:network.odp-index />
            @endif
        </div>
    </div>
</div>
