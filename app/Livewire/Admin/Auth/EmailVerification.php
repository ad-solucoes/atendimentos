<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

// Sua notificação customizada

class EmailVerification extends Component
{
    public $verified = false;

    public $error = false;

    public $message = '';

    // Propriedade para armazenar o ID do usuário para reenviar, se necessário.
    // Pode ser populada via o construtor ou método mount se o usuário não estiver logado.
    public $userId;

    public function mount(Request $request, $id, $hash)
    {
        // Se o usuário não está autenticado, tenta encontrá-lo para a verificação.
        // O `Auth::user()` é null aqui se o usuário ainda não logou.
        $user = Auth::user();

        // Se o usuário não estiver logado e o ID não corresponde ao ID no link,
        // ou se o hash não corresponde, o link é inválido.
        if (empty($user) || $user->getKey() != $id || ! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            // Tenta encontrar o usuário pelo ID do link, mesmo que não logado.
            // Isso é útil para o caso de um usuário recém-criado que ainda não logou.
            $user = \App\Models\User::find($id);

            if (empty($user) || ! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
                $this->error   = true;
                $this->message = 'Link de verificação inválido ou expirado.';

                return;
            }
        }

        // Verifica se o e-mail já está verificado
        if ($user->hasVerifiedEmail()) {
            $this->verified = true;
            $this->message  = 'Seu email já foi verificado anteriormente.';

            return;
        }

        // Tenta verificar o e-mail
        if ($user->markEmailAsVerified()) {
            $this->verified = true;
            $this->message  = 'Seu email foi verificado com sucesso!';
            // Dispara o evento Verified para Laravel (opcional)
            // event(new \Illuminate\Auth\Events\Verified($user));
        } else {
            $this->error   = true;
            $this->message = 'Não foi possível verificar seu email. O link pode ser inválido ou já foi utilizado.';
        }

        // Armazena o ID do usuário para o caso de reenviar o e-mail
        $this->userId = $user->getKey();
    }

    #[Layout('layouts.auth')]
    public function render()
    {
        return view('livewire.admin.auth.email-verification');
    }

    public function continueToSystem()
    {
        $user = Auth::user();

        request()->session()->regenerate();

        toastr()->success("Bem-vindo(a) ao sistema, {$user->name}!");

        // Check email verification
        if (! $user->hasVerifiedEmail()) {
            return $this->redirect('/admin/auth/verify-email', navigate: true);
        }

        // Check if must change password
        if ($user->mustChangePassword()) {
            return $this->redirect('/admin/auth/change-password', navigate: true);
        }

        return $this->redirect('/admin/dashboard', navigate: true);
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function resendVerificationEmail()
    {
        // Reenvia o e-mail de verificação para o usuário atual.
        // Se o usuário não está logado, usa o $userId populado no mount.
        $user = Auth::user();

        if (! $user && $this->userId) {
            $user = \App\Models\User::find($this->userId);
        }

        if ($user && ! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification(); // Chama o método do modelo User
            $this->dispatch('alert', [
                'type'    => 'success',
                'title'   => 'E-mail Enviado!',
                'message' => 'Um novo link de verificação foi enviado para seu e-mail.',
            ]);
        } else {
            $this->dispatch('alert', [
                'type'    => 'warning',
                'title'   => 'Erro no Reenvio!',
                'message' => 'Não foi possível reenviar o link de verificação. Seu e-mail pode já estar verificado.',
            ]);
        }
    }
}
