<?php

namespace App\Notifications\Auth;

use App\Broadcasting\SmsChannel;
use App\Notifications\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyPhone extends Notification
{
    use Queueable;

    public function via($notifiable): array
    {
        return [SmsChannel::class, 'database'];
    }

    public function toSms($notifiable): SmsMessage
    {
        return (new SmsMessage)
            ->to(phone_cleaner($notifiable->getAttribute('phone')))
            ->line( trans('auth.verify-sms', ['code' => $notifiable->getAttribute('verify_code'), 'minute' => 5]));
    }

    public function toArray($notifiable): array
    {
        return [
            'phone' => phone_cleaner($notifiable->getAttribute('phone')),
            'content' => trans('auth.verify-sms', ['code' => $notifiable->getAttribute('verify_code'), 'minute' => 5])
        ];
    }
}
