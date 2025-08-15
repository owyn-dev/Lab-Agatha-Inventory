<?php

declare(strict_types=1);

namespace App\Livewire\ManageAccess;

use App\Enums\UserRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;

final class FormCreateUser extends Component
{
    public $full_name = '';

    public $username = '';

    public $role = '';

    public $password = '';

    public $password_confirmation = '';

    public function render()
    {
        return view('livewire.manage-access.form-create-user');
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

    public function save(): void
    {
        $this->validate();

        $user = User::create([
            'full_name' => $this->full_name,
            'username' => $this->username,
            'password' => $this->password,
        ]);

        $user->syncRoles($this->role);

        $this->reset();

        flash()->info('Data saved successfully.');
    }

    protected function rules()
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'unique:users,username', 'max:255'],
            'password' => ['required', 'min:6', 'confirmed'],
            'role' => ['required', Rule::in(UserRole::values())],
        ];
    }
}
