<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class CustomVerifyEmail extends VerifyEmailBase
{
    use Queueable;

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $verificationCode = rand(100000, 999999); // Генерируем 6-значный код
        $expiresAt = Carbon::now()->addMinutes(30); // Код действителен 30 минут
        
        // Сохраняем код в кэш с ключом по email пользователя
        Cache::put(
            'email_verification:'.$notifiable->email, 
            [
                'code' => $verificationCode,
                'user_id' => $notifiable->id,
            ],
            $expiresAt
        );

        return (new MailMessage)
            ->subject('Подтверждение электронной почты')
            ->greeting('Здравствуйте!')
            ->line('Вы получили это письмо, потому что зарегистрировались на нашем сайте.')
            ->line('Ваш код подтверждения: ' . $verificationCode)
            ->line('Код действителен в течение 30 минут.')
            ->line('Если вы не регистрировались на нашем сайте, никаких дальнейших действий не требуется.')
            ->line('С уважением,')
            ->line('Андреев Виталий')
            ->view('emails.verify', ['code' => $verificationCode]);
    }
}