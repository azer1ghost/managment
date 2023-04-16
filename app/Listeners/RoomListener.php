<?php

namespace App\Listeners;

use Illuminate\Broadcasting\Channel;

class RoomListener
{
    public function handle($event)
    {
        return new Channel('room');
    }
}
