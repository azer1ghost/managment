<?php

namespace App\Console;

use App\Jobs\ProcessStatistics;
use App\Jobs\ProcessWeather;
use App\Jobs\ProcessWidgets;
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
//        $schedule->job(ProcessWidgets::class)->everyTwoHours()->environments(['staging', 'production']);
//        $schedule->job(ProcessWidgets::class)->everyMinute()->environments(['testing', 'local']);

        $schedule->job(ProcessStatistics::class)->everyTwoHours()->environments(['staging', 'production']);
        $schedule->job(ProcessStatistics::class)->everyMinute()->environments(['testing', 'local']);

//        $schedule->job(ProcessWeather::class)->everyThreeHours()->environments(['staging', 'production']);
//        $schedule->job(ProcessWeather::class)->everyTwoMinutes()->environments(['testing', 'local']);
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
