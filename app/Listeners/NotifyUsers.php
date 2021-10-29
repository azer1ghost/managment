<?php

namespace App\Listeners;

use App\Events\Notification;
use App\Services\FirebaseApi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUsers implements ShouldQueue
{
    /**
     * The number of times the queued listener may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    public function __construct()
    {
        //
    }

    public function handle(Notification $event)
    {
        (new FirebaseApi)->sendNotification($event->sender, $event->notifiables, $event->title, $event->body, $event->route);
    }
}
