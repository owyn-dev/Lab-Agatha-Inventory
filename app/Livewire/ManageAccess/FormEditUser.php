<?php

declare(strict_types=1);

namespace App\Livewire\ManageAccess;

use App\Enums\UserRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;

final class FormEditUser extends Component
{
    public User $user;

    public $full_name = '';

    public $username = '';

    public $role = '';

    public $password = '';

    public $password_confirmation = '';

    public function mount(User $user): void
    {
        $this->user = $user;

        $this->full_name = $user->full_name;
        $this->username = $user->username;
        $this->role = $user->getRoleNames()->first();
    }

    public function render()
    {
        return view('livewire.manage-access.form-edit-user');
    }

    public function placeholder()
    {
        return view('components.placeholders.loading');
    }

    #[Computed]
    public function roles()
    {
        return Role::pluck('name');
    }

    public function update(): void
    {
        $this->validate();

        $this->user->update([
            'full_name' => $this->full_name,
            'username' => $this->username,
            'password' => $this->password ? $this->password : $this->user->password,
        ]);

        $this->user->syncRoles([$this->role]);

        $this->reset('password');

        flash()->info('Data updated successfully.');
    }

    protected function rules()
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'username' => ['required', Rule::unique('users', 'username')->ignore($this->user->id), 'max:255'],
            'password' => ['nullable', 'min:6', 'confirmed'],
            'role' => ['required', Rule::in(UserRole::values())],
        ];
    }
}
