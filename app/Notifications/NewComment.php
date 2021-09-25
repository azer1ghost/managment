<?php

namespace App\Notifications;

use App\Broadcasting\DatabaseChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewComment extends Notification
{
    use Queueable;

    public string $message;

    public function __construct($message)
    {
        $this->message = $message;
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
            'message' => trans('translates.comments.new'),
            'user_id' => auth()->id(),
            'content' => $this->message,
        ];
    }
}
