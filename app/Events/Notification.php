<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Notification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $sender;
    public array $notifiables;
    public string $route, $title, $body;

    public function __construct($sender, $notifiables, $title, $body, $route)
    {
        $this->sender = $sender;
        $this->notifiables = $notifiables;
        $this->route = $route;
        $this->title = $title;
        $this->body = $body;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
