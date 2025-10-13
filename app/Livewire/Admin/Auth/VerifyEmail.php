<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VerifyEmail extends Component
{
    public function resend()
    {
        Auth::user()->sendEmailVerificationNotification();
        session()->flash('message', 'Email de verificação reenviado!');
    }

    public function render()
    {
        return view('livewire.admin.auth.verify-email')->layout('layouts.auth');
    }
}
