<?php

namespace App\Console;

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
        'App\Console\Commands\DailyReport',
        'App\Console\Commands\WeeklyReport',
        'App\Console\Commands\ReportTest',
        'App\Console\Commands\EveryMinute',
        'App\Console\Commands\MonthlyReport',
        'App\Console\Commands\TodosFrequency',
        'App\Console\Commands\TodosFrequencyCheck',
        'App\Console\Commands\ClientStatus',
        'App\Console\Commands\JobOpentoAll',
        'App\Console\Commands\TrainingSelectAllUser',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        //$schedule->command('report:daily')->dailyAt('20:00');
        //$schedule->command('report:weekly')->weeklyOn(6, '20:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
