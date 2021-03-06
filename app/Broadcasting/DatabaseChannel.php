<?php

namespace App\Broadcasting;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;

class DatabaseChannel extends IlluminateDatabaseChannel
{
    public function send($notifiable, Notification $notification)
    {
        return $notifiable->routeNotificationFor('database')->create(
            array_merge($this->buildPayload($notifiable, $notification), [
                'channel' => method_exists($notification, 'getChannel') ? $notification->getChannel() : null,
                'provider' => method_exists($notification, 'getProvider') ? $notification->getProvider() : null
            ])
        );
    }
}