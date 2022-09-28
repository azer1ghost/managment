<?php

namespace App\Notifications;

use App\Notifications\Messages\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NotifyClientSms extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['sms'];
    }

    public function toSms($notifiable)
    {
        $message = now();
        return (new SmsMessage)
            ->to(phone_cleaner($notifiable->getAttribute('phone')))
            ->line('The introduction to the asnn.'. $message);
    }
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
