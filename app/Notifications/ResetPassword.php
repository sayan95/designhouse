<?php

namespace App\Notifications;


use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as NotificationResetPassword;

class ResetPassword extends NotificationResetPassword
{
    public function toMail($notifiable)
    {
        $url = url(config('app.client_url').'/password/reset/'.$this->token).'?email='.urlencode($notifiable->email);
        return (new MailMessage)
            ->subject(Lang::get('Reset Password '))
            ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
            ->action(Lang::get('Reset Password'), $url)
            ->line(Lang::get('If you did not request a password reset, no further action is required.'));
    }
}
