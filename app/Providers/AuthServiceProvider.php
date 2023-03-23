<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function ($notifiable, $verificationUrl) {
            return (new MailMessage)
                ->subject('Verifición de cuenta')
                ->line('haga clic en el botón de abajo para verificar su dirección de correo electrónico.')
                ->action('Verificar Email',  $verificationUrl)
                ->line('Si no creó una cuenta, no es necesario realizar ninguna otra acción.');
        });

        ResetPassword::toMailUsing(function ($notifiable, $token) {

            $resetUrl = route('password.reset', ['token' => $token, 'email' => $notifiable->getEmailForPasswordReset()]);

            return (new MailMessage)
                ->subject('Restablecimiento de contraseña')
                ->line('Está recibiendo este correo electrónico porque recibimos una solicitud de restablecimiento de contraseña para su cuenta.')
                ->action('Restablecer la contraseña', $resetUrl)
                ->line('Este enlace de restablecimiento de contraseña caducará en 60 minutos.')
                ->line('Si no solicitó un restablecimiento de contraseña, no se requiere ninguna otra acción.');
        });
    }
}
