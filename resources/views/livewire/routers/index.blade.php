<div>
    <!-- Header Area -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Router Mikrotik</h2>
            <p class="text-sm text-slate-500">Kelola koneksi ke router Mikrotik Anda</p>
        </div>
        <x-button label="Tambah Router" icon="o-plus" class="btn-primary shadow-lg shadow-primary/20 w-full sm:w-auto" wire:click="create" />
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <x-table :headers="$headers" :rows="$routers" with-pagination class="text-sm min-w-[700px] sm:min-w-full">
            @scope('cell_name', $router)
                <div class="flex items-center gap-3">
                    <div class="bg-slate-100 p-2 rounded-lg text-slate-500">
                        <x-icon name="o-server" class="w-5 h-5" />
                    </div>
                    <span class="font-semibold text-slate-800">{{ $router->name }}</span>
                </div>
            @endscope

            @scope('cell_ip_address', $router)
                 <span class="font-mono text-slate-600">{{ $router->ip_address }}:{{ $router->port }}</span>
            @endscope

            @scope('cell_status', $router)
                @if($router->status === 'online')
                    <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                        <span class="size-1.5 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                        Connected
                    </div>
                @else
                    <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500 border border-slate-200">
                        <span class="size-1.5 rounded-full bg-slate-400 mr-2"></span>
                        Offline
                    </div>
                @endif
            @endscope

            @scope('cell_customers_count', $router)
                <span class="inline-flex items-center px-2 py-1 rounded-md bg-blue-50 text-blue-700 text-xs font-medium">
                    {{ $router->customers_count }} Pelanggan
                </span>
            @endscope

            @scope('actions', $router)
                <div class="flex gap-1 justify-end">
                    <x-button icon="o-pencil" class="btn-sm btn-ghost text-slate-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all" wire:click="edit({{ $router->id }})" />
                    <x-button icon="o-trash" class="btn-sm btn-ghost text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" wire:click="delete({{ $router->id }})" wire:confirm="Yakin ingin menghapus router ini?" />
                </div>
            @endscope
        </x-table>
    </div>

    <!-- Modal for Create/Edit -->
    <x-modal wire:model="routerModal" title="{{ $editingRouter ? 'Edit Router' : 'Tambah Router Baru' }}" subtitle="Konfigurasi koneksi API Mikrotik" separator>
        <div class="grid gap-4">
            <x-input label="Nama Router" wire:model="name" placeholder="Contoh: Core Router Jakarta" icon="o-server" />
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-2">
                    <x-input label="IP Address" wire:model="ip_address" placeholder="192.168.1.1" icon="o-globe-alt" />
                </div>
                <div>
                     <x-input label="Port API" wire:model="port" placeholder="8728" />
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <x-input label="Username" wire:model="username" placeholder="admin" icon="o-user" />
                <x-input label="Password" wire:model="password" type="password" placeholder="••••••" icon="o-key" />
            </div>

            <!-- Test Connection Section -->
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 mt-2">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm font-semibold text-slate-700">Test Koneksi</span>
                    <x-button label="Test Ping & API" icon="o-bolt" class="btn-sm btn-outline btn-primary" wire:click="testConnection" spinner="testConnection" />
                </div>
                
                @if($testResult)
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between border-b border-slate-200 pb-1">
                            <span class="text-slate-500">Board Name</span>
                            <span class="font-mono font-medium text-slate-800">{{ $testResult['board-name'] ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 pb-1">
                            <span class="text-slate-500">Version</span>
                            <span class="font-mono font-medium text-slate-800">{{ $testResult['version'] ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 pb-1">
                            <span class="text-slate-500">CPU Load</span>
                            <span class="font-mono font-medium text-slate-800">{{ $testResult['cpu-load'] ?? 0 }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Uptime</span>
                            <span class="font-mono font-medium text-slate-800">{{ $testResult['uptime'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="mt-3 p-2 bg-green-100 text-green-700 rounded-lg text-xs font-medium flex items-center gap-2">
                        <x-icon name="o-check-circle" class="w-4 h-4" />
                        Koneksi ke Mikrotik berhasil!
                    </div>
                @endif
            </div>
        </div>
 
        <x-slot:actions>
            <x-button label="Batal" @click="$wire.routerModal = false" />
            <x-button label="Simpan Router" class="btn-primary" wire:click="save" spinner="save" />
        </x-slot:actions>
    </x-modal>
</div>
