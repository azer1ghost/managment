<?php

namespace App\Console;

use App\Jobs\InquiryAlarm;
use App\Jobs\ProcessStatistics;
use App\Jobs\ProcessWeather;
use App\Jobs\StatisticsResolver;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('model:prune')->daily();

        $schedule->job(InquiryAlarm::class)->everyMinute()->environments(['staging', 'production']);
        $schedule->job(InquiryAlarm::class)->everyMinute()->environments(['testing', 'local']);

        $schedule->job(ProcessStatistics::class)->everyFourHours()->environments(['staging', 'production']);
        $schedule->job(ProcessStatistics::class)->everyMinute()->environments(['testing', 'local']);

        $schedule->job(StatisticsResolver::class)->everyMinute()->environments(['staging', 'production']);
        $schedule->job(StatisticsResolver::class)->everyMinute()->environments(['testing', 'local']);

        $schedule->job(ProcessWeather::class)->hourly()->environments(['staging', 'production']);
        $schedule->job(ProcessWeather::class)->everyTwoMinutes()->environments(['testing', 'local']);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
