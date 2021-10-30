<?php

namespace App\Listeners;

use App\Services\FirebaseApi;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNotification implements ShouldQueue
{
    public int $tries = 3;

    public function handle($event)
    {
        (new FirebaseApi)->sendNotification($event->creator, $event->receivers, $event->title, $event->body, $event->url);
    }
}