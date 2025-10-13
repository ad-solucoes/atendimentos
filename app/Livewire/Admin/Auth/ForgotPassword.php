<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ForgotPassword extends Component
{
    public $email;

    public function sendResetLink()
    {
        $this->validate(['email' => 'required|email']);
        $status = Password::sendResetLink(['email' => $this->email]);

        session()->flash('message', __($status));
    }

    public function render()
    {
        return view('livewire.admin.auth.forgot-password')->layout('layouts.auth');
    }
}
