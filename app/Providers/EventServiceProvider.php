<?php

namespace App\Providers;

use App\Events\RegistrationLogCreated;
use App\Events\TaskCreated;
use App\Events\TaskListCreated;
use App\Events\TaskStatusDone;
use App\Events\WorkCreated;
use App\Events\WorkStatusRejected;
use App\Listeners\SendNotification;
use App\Listeners\SendPushNotification;
use App\Models\DailyReport;
use App\Models\Inquiry;
use App\Models\Report;
use App\Models\Task;
use App\Models\User;
use App\Models\Work;
use App\Observers\DailyReportObserver;
use App\Observers\InquiryObserver;
use App\Observers\ReportObserver;
use App\Observers\TaskObserver;
use App\Observers\UserObserver;
use App\Observers\WorkObserver;
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
        WorkCreated::class => [
            SendNotification::class,
            SendPushNotification::class,
        ],
        WorkStatusRejected::class => [
            SendNotification::class,
            SendPushNotification::class,
        ],
        RegistrationLogCreated::class => [
            SendNotification::class,
            SendPushNotification::class,
//            SendEmailNotification::class,
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
        Work::observe(WorkObserver::class);
        Task::observe(TaskObserver::class);
        Report::observe(ReportObserver::class);
        DailyReport::observe(DailyReportObserver::class);
        Inquiry::observe(InquiryObserver::class);
        User::observe(UserObserver::class);
    }
}
