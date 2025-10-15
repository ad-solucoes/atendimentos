<?php

declare(strict_types = 1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

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
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage())
            ->subject('📧 Verificação de Email - Sistema')
            ->greeting("Olá, {$notifiable->name}!")
            ->line('Você está recebendo este email para verificar seu endereço de email.')
            ->line('')
            ->line('🔒 **Para verificar seu email e acessar o sistema, clique no botão abaixo:**')
            ->action('✅ Verificar Email', $verificationUrl)
            ->line('')
            ->line('⏰ **Este link de verificação expira em 60 minutos.**')
            ->line('')
            ->line('Se você não conseguir clicar no botão, copie e cole a URL abaixo no seu navegador:')
            ->line($verificationUrl)
            ->line('')
            ->line('🔐 **Por que verificar o email?**')
            ->line('• Garante a segurança da sua conta')
            ->line('• Permite recuperação de senha se necessário')
            ->line('• Confirma que você tem acesso ao email informado')
            ->line('• É obrigatório para acessar o sistema')
            ->line('')
            ->line('❓ **Se você não se cadastrou em nosso sistema, ignore este email.**')
            ->line('')
            ->salutation('Atenciosamente,<br>Equipe do Sistema');
    }

    /**
     * Get the verification URL for the given notifiable.
     */
    protected function verificationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'admin.verification.verify',
            now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id'   => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'email_verification',
            'message' => "Email de verificação enviado para {$notifiable->email}",
            'email'   => $notifiable->email,
        ];
    }
}
