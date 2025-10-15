<?php

declare(strict_types = 1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
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
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], true));

        return (new MailMessage())
            ->subject('🔐 Recuperação de Senha - Sistema')
            ->greeting("Olá, {$notifiable->name}!")
            ->line('Você está recebendo este email porque solicitou a recuperação de senha para sua conta.')
            ->line('')
            ->line('🔒 **Para criar uma nova senha, clique no botão abaixo:**')
            ->action('🔄 Redefinir Senha', $resetUrl)
            ->line('')
            ->line('⏰ **Este link de recuperação expira em 60 minutos.**')
            ->line('')
            ->line('Se você não conseguir clicar no botão, copie e cole a URL abaixo no seu navegador:')
            ->line($resetUrl)
            ->line('')
            ->line('🛡️ **Dicas de segurança:**')
            ->line('• Use uma senha forte com pelo menos 8 caracteres')
            ->line('• Combine letras maiúsculas, minúsculas, números e símbolos')
            ->line('• Não compartilhe sua senha com ninguém')
            ->line('• Use senhas diferentes para cada serviço')
            ->line('')
            ->line('❓ **Se você não solicitou esta recuperação de senha, ignore este email.**')
            ->line('Sua senha atual permanecerá inalterada.')
            ->line('')
            ->salutation('Atenciosamente,<br>Equipe do Sistema');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'password_reset',
            'message' => "Email de recuperação de senha enviado para {$notifiable->email}",
            'email'   => $notifiable->email,
        ];
    }
}
