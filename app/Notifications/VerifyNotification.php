<?php

namespace App\Notifications;

use App\Broadcasting\SmsChannel;
use App\SmsProviders\PoctGoyercini;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyNotification extends Notification
{
    use Queueable;

    public function via($notifiable): array
    {
        return [SmsChannel::class, 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                ->line($notifiable->getAttribute('verify_code'));
    }

    public function toSms($notifiable): PoctGoyercini
    {
        return (new PoctGoyercini)
            ->to($notifiable->getAttribute('phone_coop'))
            ->line($notifiable->getAttribute('verify_code'));
    }

}
