<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ResetPassword extends Component
{
    public $token;

    public $email;

    public $password;

    public $password_confirmation;

    public $status = '';

    public function mount($token)
    {
        $this->token = $token;
    }

    public function resetPassword()
    {
        $this->validate([
            'email'    => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $status = Password::reset(
            [
                'email'                 => $this->email,
                'password'              => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token'                 => $this->token,
            ],
            function ($user, $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        $this->status = $status === Password::PASSWORD_RESET
            ? 'Senha redefinida com sucesso.'
            : 'Falha ao redefinir senha.';
    }

    public function render()
    {
        return view('livewire.admin.auth.reset-password')->layout('layouts.auth', ['title' => 'Redefinir Senha']);
    }
}
