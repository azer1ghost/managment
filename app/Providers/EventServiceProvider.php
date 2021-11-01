<?php

namespace App\Providers;

use App\Events\CommentCreated;
use App\Events\TaskCreated;
use App\Events\Notification;
use App\Events\TaskListCreated;
use App\Events\TaskListDone;
use App\Events\TaskStatusUpdated;
use App\Listeners\NotifyUsers;
use App\Listeners\SendEmailNotification;
use App\Listeners\SendNotification;
use App\Listeners\SendPushNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        TaskCreated::class => [
            SendNotification::class,
            SendPushNotification::class,
            SendEmailNotification::class,
        ],
        TaskStatusUpdated::class => [
            SendNotification::class,
            SendPushNotification::class,
//            SendEmailNotification::class,
        ],
        TaskListCreated::class => [
            SendNotification::class,
            SendPushNotification::class,
//            SendEmailNotification::class,
        ],
        TaskListDone::class => [
            SendNotification::class,
            SendPushNotification::class,
//            SendEmailNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
