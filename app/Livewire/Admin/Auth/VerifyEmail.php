<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Auth;

use Illuminate\Support\Facades\Auth;
use App\Models\Log;
use Livewire\Component;

class VerifyEmail extends Component
{
    public bool $emailSent = false;

    public function mount()
    {
        // Se o email já foi verificado, redireciona para o dashboard
        if (auth()->user() && auth()->user()->hasVerifiedEmail()) {
            return $this->redirect('/admin/dashboard', navigate: true);
        }
    }

    public function resend()
    {
        $user = auth()->user();

        if ($user->hasVerifiedEmail()) {
            $this->dispatch('alert', [
                'type'    => 'info',
                'title'   => 'Informação!',
                'message' => 'Seu email já foi verificado.',
            ]);

            return $this->redirect('/admin/dashboard', navigate: true);
        }

        Auth::user()->sendEmailVerificationNotification();

        $this->emailSent = true;

        $this->dispatch('alert', [
            'type'    => 'success',
            'title'   => 'Sucesso!',
            'message' => '📧 Email de verificação enviado! Verifique sua caixa de entrada.',
        ]);

        Log::logMessage("Email de verificação reenviado para {$user->email}");
    }

    public function render()
    {
        return view('livewire.admin.auth.verify-email')->layout('layouts.auth');
    }
}
