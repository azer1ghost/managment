<?php

namespace App\Notifications;

use App\Notifications\Messages\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NotifyClientAccountantSms extends Notification implements ShouldQueue
{
    use Queueable;
    private $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['sms'];
    }

    public function toSms($notifiable)
    {
        return (new SmsMessage)
            ->to(phone_cleaner($notifiable->getAttribute('phone3')))
            ->line($this->message);
    }
}
