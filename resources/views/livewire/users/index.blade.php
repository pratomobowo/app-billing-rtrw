<div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Manajemen Pengguna</h2>
            <p class="text-sm text-slate-500">Kelola akses admin dan operator sistem</p>
        </div>
        <div>
             <x-button label="Tambah User" icon="o-plus" class="btn-primary shadow-lg shadow-primary/20" wire:click="create" />
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase tracking-wider text-xs border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3">Nama Lengkap</th>
                        <th class="px-6 py-3">Role</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Terakhir Aktivitas</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="size-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                                         {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-medium text-slate-900">{{ $user->name }}</span>
                                        <span class="text-xs text-slate-500">{{ $user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">Admin</span>
                                @elseif($user->role === 'finance')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Finance</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Operator</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-50 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-slate-500 text-xs">
                                -
                            </td>
                            <td class="px-6 py-3 text-right">
                                <div class="flex gap-1 justify-end">
                                    <x-button icon="o-pencil" class="btn-sm btn-circle btn-ghost text-slate-400 hover:text-primary" wire:click="edit({{ $user->id }})" />
                                    @if($user->id !== auth()->id())
                                        <x-button icon="o-trash" class="btn-sm btn-circle btn-ghost text-slate-400 hover:text-red-500" wire:click="delete({{ $user->id }})" wire:confirm="Hapus user ini?" />
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    <x-modal wire:model="userModal" title="{{ $editingUser ? 'Edit User' : 'Tambah User Baru' }}" separator>
        <div class="space-y-4">
            <x-input label="Nama Lengkap" wire:model="name" />
            <x-input label="Email Address" wire:model="email" type="email" />
            <x-input label="Password" wire:model="password" type="password" hint="{{ $editingUser ? 'Kosongkan jika tidak ingin mengubah password' : 'Minimal 6 karakter' }}" />
            
            <x-select label="Role Akses" wire:model="role" :options="[
                ['id' => 'admin', 'name' => 'Administrator (Full Access)'],
                ['id' => 'operator', 'name' => 'Operator (Teknisi)'],
                ['id' => 'finance', 'name' => 'Finance (Billing Only)'],
            ]" option-label="name" option-value="id" />

            <x-toggle label="User Aktif" wire:model="is_active" class="toggle-primary" />
        </div>
        <x-slot:actions>
            <x-button label="Batal" @click="$wire.userModal = false" />
            <x-button label="Simpan" class="btn-primary" wire:click="save" spinner="save" />
        </x-slot:actions>
    </x-modal>
</div>
