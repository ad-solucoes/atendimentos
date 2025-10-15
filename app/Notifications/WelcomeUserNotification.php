<?php

declare(strict_types = 1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class WelcomeUserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $temporaryPassword;

    public $verificationUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $temporaryPassword)
    {
        $this->temporaryPassword = $temporaryPassword;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Gera URL de verificação
        $verificationUrl = URL::temporarySignedRoute(
            'admin.verification.verify',
            now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id'   => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        return (new MailMessage())
            ->subject('🎉 Bem-vindo(a) ao Sistema - Dados de Acesso')
            ->greeting("Olá, {$notifiable->name}!")
            ->line('Seja bem-vindo(a) ao nosso sistema! Sua conta foi criada com sucesso.')
            ->line('**Seus dados de acesso:**')
            ->line("📧 **Email:** {$notifiable->email}")
            ->line("🔐 **Senha temporária:** `{$this->temporaryPassword}`")
            ->line('')
            ->line('⚠️ **IMPORTANTE:** Por segurança, você deve alterar esta senha no primeiro acesso.')
            ->line('')
            ->line('🔒 **Para acessar o sistema, você precisa verificar seu email primeiro:**')
            ->action('✅ Verificar Email', $verificationUrl)
            ->line('')
            ->line('📝 **Próximos passos:**')
            ->line('1. Clique no botão acima para verificar seu email')
            ->line('2. Faça login com os dados fornecidos')
            ->line('3. Altere sua senha no primeiro acesso')
            ->line('4. Complete seu perfil se necessário')
            ->line('')
            ->line('Se você não conseguir clicar no botão, copie e cole a URL abaixo no seu navegador:')
            ->line($verificationUrl)
            ->line('')
            ->line('⏰ **Este link expira em 60 minutos.**')
            ->line('')
            ->salutation('Atenciosamente,<br>Equipe do Sistema');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'welcome',
            'message' => "Usuário {$notifiable->name} foi criado e dados enviados por email",
            'email'   => $notifiable->email,
        ];
    }
}
