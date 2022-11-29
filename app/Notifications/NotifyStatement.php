<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NotifyStatement extends Notification
{
    use Queueable;

    public function __construct($title, $body, $attribute)
    {
        $this->title = $title;
        $this->body = $body;
        $this->attribute = $attribute;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'attribute' => $this->attribute,
        ];
    }
}
