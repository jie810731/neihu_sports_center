<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Log;
use App\Jobs\Login;
use App\Jobs\GetTicket;
use App\Jobs\GetOrderLists;
use App\Jobs\GetTicketMultiProcess;

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
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            Log::info('test schedule');
        })->dailyAt('01:00');

        //$schedule->job(new GetTicketMultiProcess)->everyMinute();

        $schedule->job(new Login)->dailyAt('23:55');
        $schedule->job(new GetTicket)->dailyAt('23:59');
        $schedule->job(new GetTicketMultiProcess)->dailyAt('23:58');
        $schedule->job(new GetOrderLists)->dailyAt('00:01');
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
