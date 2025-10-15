<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Auth;

use App\Models\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class ChangePassword extends Component
{
    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount()
    {
        // Se não precisar alterar senha e email verificado, redireciona
        $user = auth()->user();

        if (! $user->mustChangePassword() && $user->hasVerifiedEmail()) {
            return $this->redirect('/admin/dashboard', navigate: true);
        }
    }

    protected function rules()
    {
        return [
            'current_password' => 'required|current_password',
            'password'         => [
                'required',
                'confirmed',
                PasswordRule::min(8)
                    // ->letters()
                    // ->mixedCase()
                    ->numbers(),
                // ->symbols()
                // ->uncompromised()
            ],
        ];
    }

    protected $messages = [
        'current_password.required'         => 'O campo senha atual é obrigatório.',
        'current_password.current_password' => 'A senha atual está incorreta.',
        'password.required'                 => 'O campo nova senha é obrigatório.',
        'password.min'                      => 'A nova senha deve ter pelo menos 8 caracteres.',
        'password.letters'                  => 'A nova senha deve conter pelo menos uma letra.',
        'password.mixed_case'               => 'A nova senha deve conter letras maiúsculas e minúsculas.',
        'password.numbers'                  => 'A nova senha deve conter pelo menos um número.',
        'password.symbols'                  => 'A nova senha deve conter pelo menos um símbolo.',
        'password.uncompromised'            => 'A senha escolhida foi comprometida em vazamentos de dados. Escolha uma senha diferente.',
        'password.confirmed'                => 'A confirmação da senha não confere.',
    ];

    public function updatePassword()
    {
        $this->validate();

        $user = auth()->user();

        // Update password
        $user->update([
            'password' => Hash::make($this->password),
        ]);

        // Mark password as changed if it was temporary
        if ($user->mustChangePassword()) {
            $user->markPasswordAsChanged();
            Log::logMessage("Usuário {$user->name} alterou senha temporária");

            $this->dispatch('alert', [
                'type'    => 'success',
                'title'   => 'Sucesso!',
                'message' => '🔐 Senha alterada com sucesso! Bem-vindo(a) ao sistema.',
            ]);
        } else {
            Log::logMessage("Usuário {$user->name} alterou sua senha");

            $this->dispatch('alert', [
                'type'    => 'success',
                'title'   => 'Sucesso!',
                'message' => '🔐 Senha alterada com sucesso!',
            ]);
        }

        // Clear form
        $this->reset(['current_password', 'password', 'password_confirmation']);

        return $this->redirect('/admin/dashboard', navigate: true);
    }

    public function logout()
    {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return $this->redirect('/admin/login', navigate: true);
    }

    #[Title('Alterar Senha')]
    #[Layout('layouts.auth')]
    public function render()
    {
        $user = auth()->user();

        return view('livewire.admin.auth.change-password', [
            'mustChange' => $user->mustChangePassword(),
        ]);
    }
}
