<?php

namespace App\Broadcasting;

use Illuminate\Notifications\Notification;

class SmsChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $notification
            ->toSms($notifiable)
            ->dryRun(
                app()->environment('local')
            )
            ->send();
    }
}
