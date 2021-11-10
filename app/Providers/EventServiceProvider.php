<?php

namespace App\Providers;

use App\Events\TaskCreated;
use App\Events\TaskListCreated;
use App\Events\TaskListDone;
use App\Events\TaskStatusDone;
use App\Events\TaskStatusUpdated;
use App\Listeners\SendNotification;
use App\Listeners\SendPushNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
//            SendEmailNotification::class,
        ],
        TaskStatusDone::class => [
            SendNotification::class,
            SendPushNotification::class,
//            SendEmailNotification::class,
        ],
        TaskListCreated::class => [
            SendNotification::class,
            SendPushNotification::class,
//            SendEmailNotification::class,
        ],
//        TaskListDone::class => [
//            SendNotification::class,
//            SendPushNotification::class,
//            SendEmailNotification::class,
//        ],
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
