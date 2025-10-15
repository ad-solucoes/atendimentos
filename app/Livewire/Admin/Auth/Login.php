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
            $user = Auth::user();

            request()->session()->regenerate();

            toastr()->success("Bem-vindo(a) ao sistema, {$user->name}!");

            // Check email verification
            if (! $user->hasVerifiedEmail()) {
                return redirect('/admin/auth/verify-email');
            }

            // Check if must change password
            if ($user->mustChangePassword()) {
                return redirect('/admin/auth/change-password');
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        $this->addError('email', 'As credenciais fornecidas não correspondem aos nossos registros.');
    }

    public function render()
    {
        return view('livewire.admin.auth.login')->layout('layouts.auth', ['title' => 'Login']);
    }
}
