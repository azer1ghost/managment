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
        return [SmsChannel::class];
    }

    public function toSms($notifiable): SmsMessage
    {
        return (new SmsMessage)
            ->to($notifiable->getAttribute('phone_coop'))
            ->line( trans('auth.verify-sms', ['code' => $notifiable->getAttribute('verify_code'), 'minute' => 5]));
    }
}
