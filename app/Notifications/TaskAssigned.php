<?php

namespace App\Notifications;

use App\Broadcasting\DatabaseChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class TaskAssigned extends Notification
{
    use Queueable;

    public string $message, $url, $type;

    public function __construct($message, $url, $type)
    {
        $this->message = $message;
        $this->url = $url;
        $this->type = $type;
    }

    public function getChannel(): string
    {
        return 'DATABASE';
    }

    public function via($notifiable): array
    {
        return [DatabaseChannel::class];
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => $this->type,
            'user_id' => auth()->id(),
            'content' => $this->message,
            'url' => $this->url
        ];
    }
}
