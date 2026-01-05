<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Email Doğrulama')
            ->greeting('Salam ' . $notifiable->name . '!')
            ->line('Hesabınızı aktivləşdirmək üçün aşağıdakı doğrulama kodunu istifadə edin:')
            ->line('**Doğrulama kodu: ' . $notifiable->verify_code . '**')
            ->line('Bu kod 5 dəqiqə ərzində etibarlıdır.')
            ->line('Əgər siz bu hesabı yaratmamısınızsa, bu mesajı göz ardı edə bilərsiniz.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            'email' => $notifiable->email,
            'code' => $notifiable->verify_code,
        ];
    }
}

