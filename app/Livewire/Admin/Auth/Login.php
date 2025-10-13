<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email = 'admin@email.com';

    public $password = 'password';

    public $remember = false;

    public function login()
    {
        $this->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            return redirect()->intended(route('admin.dashboard'));
        }

        $this->addError('email', 'Credenciais inválidas.');
    }

    public function render()
    {
        return view('livewire.admin.auth.login')->layout('layouts.auth');
    }
}
