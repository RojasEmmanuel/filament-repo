<?php

namespace App\Notifications;

use Filament\Facades\Filament;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class CustomResetPasswordNotification extends Notification
{
    public function __construct(
        private readonly string $token
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = Filament::getPanel('admin')->getResetPasswordUrl($this->token, $notifiable);
        $expireMinutes = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');
        
        return (new MailMessage)
            ->subject('🔐 Recupera tu contraseña - ' . config('app.name'))
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Recibiste este correo porque solicitaste restablecer la contraseña de tu cuenta.')
            ->line('Para continuar, haz clic en el siguiente botón:')
            ->action('Restablecer Contraseña', $resetUrl)
            ->line('Este enlace expirará en ' . $expireMinutes . ' minutos.')
            ->line('Si no solicitaste este cambio, puedes ignorar este mensaje.')
            ->line('Tu contraseña actual seguirá siendo válida hasta que accedas al enlace.')
            ->salutation('Saludos, el equipo de ' . config('app.name'));
    }
}
