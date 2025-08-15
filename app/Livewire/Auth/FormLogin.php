<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class FormLogin extends Component
{
    public $username;

    public $password;

    protected $rules = [
        'username' => 'required',
        'password' => 'required|min:6',
    ];

    public function render()
    {
        return view('livewire.auth.form-login');
    }

    public function placeholder()
    {
        return view('components.placeholders.loading');
    }

    public function auth()
    {
        $this->validate();

        if (Auth::attempt(['username' => $this->username, 'password' => $this->password])) {
            flash()->info('You\'re back! Keep things in order.');

            return redirect()->route('dashboard');
        }

        flash()->error('Please check your credentials and try again.');
    }
}
