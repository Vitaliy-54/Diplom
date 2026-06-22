<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CustomPasswordReset extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url(route('password.reset', ['token' => $this->token, 'email' => $notifiable->email], false));

        return (new MailMessage)
            ->subject('Сброс пароля')
            ->greeting('Здравствуйте!')
            ->line('Вы получили это письмо, потому что мы получили запрос на сброс пароля для вашей учетной записи.')
            ->action('Сбросить Пароль', $url)
            ->line('Срок действия этой ссылки для сброса пароля истечет через ' . config('auth.passwords.users.expire') . ' минут.')
            ->line('Если вы не запрашивали сброс пароля, никаких дальнейших действий не требуется.')
            ->line('Если у вас возникли проблемы с нажатием кнопки "Сбросить Пароль", скопируйте и вставьте приведенный ниже URL-адрес в свой веб-браузер: ' . $url)
            ->line('С уважением,')
            ->line('Андреев Виталий')
            ->view('emails.reset', ['url' => $url]);
    }
}