<?php

namespace App\Listeners;

use App\Events\TaskAssigned;
use App\Services\FirebaseApi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUsers
{
    public function __construct()
    {
        //
    }

    public function handle(TaskAssigned $event)
    {
        (new FirebaseApi)->sendNotification($event->sender, $event->notifiables, $event->title, $event->body, $event->route);
    }
}
