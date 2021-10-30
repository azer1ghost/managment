<?php

namespace App\Listeners;

use App\Services\FirebaseApi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPushNotification implements ShouldQueue
{
    public int $tries = 3;

    public function handle($event)
    {
        (new FirebaseApi)->sendPushNotification($event->receivers, $event->url, $event->title, $event->body);
    }
}
