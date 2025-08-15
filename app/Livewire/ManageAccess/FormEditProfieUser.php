<?php

declare(strict_types=1);

namespace App\Livewire\ManageAccess;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;

final class FormEditProfieUser extends Component
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
    }

    public function render()
    {
        return view('livewire.manage-access.form-edit-profie-user');
    }

    public function placeholder()
    {
        return view('components.placeholders.loading');
    }

    public function update(): void
    {
        $this->validate();

        $this->user->update([
            'full_name' => $this->full_name,
            'username' => $this->username,
            'password' => $this->password ? $this->password : $this->user->password,
        ]);

        $this->reset('password');

        flash()->info('Data updated successfully.');
    }

    protected function rules()
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'username' => ['required', Rule::unique('users', 'username')->ignore($this->user->id), 'max:255'],
            'password' => ['nullable', 'min:6', 'confirmed'],
        ];
    }
}
