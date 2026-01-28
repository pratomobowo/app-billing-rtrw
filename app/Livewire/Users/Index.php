<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination, Toast;

    public bool $userModal = false;
    public ?User $editingUser = null;

    // Form
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $role = 'operator';
    public bool $is_active = true;

    public function create()
    {
        $this->reset(['name', 'email', 'password', 'role', 'is_active', 'editingUser']);
        $this->userModal = true;
    }

    public function edit(User $user)
    {
        $this->editingUser = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->is_active = $user->is_active;
        $this->password = ''; // Don't show password
        $this->userModal = true;
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->editingUser?->id)],
            'role' => 'required|in:admin,operator,finance',
            'is_active' => 'boolean'
        ];

        // Validasi password hanya jika create user baru, atau jika field password diisi saat edit
        if (!$this->editingUser || $this->password) {
            $rules['password'] = 'required|min:6';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'is_active' => $this->is_active,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(
            ['id' => $this->editingUser?->id],
            $data
        );

        $this->success($this->editingUser ? 'User berhasil diperbarui' : 'User berhasil dibuat');
        $this->userModal = false;
    }

    public function delete(User $user)
    {
        if ($user->id === auth()->id()) {
            $this->error('Anda tidak bisa menghapus akun sendiri!');
            return;
        }
        $user->delete();
        $this->success('User dihapus');
    }

    public function render()
    {
        return view('livewire.users.index', [
            'users' => User::paginate(10)
        ]);
    }
}
