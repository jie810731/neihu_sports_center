<?php

namespace App\Console;

use App\Jobs\GetOrderLists;
use App\Jobs\GetTicketMultiProcess;
use App\Jobs\Login;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Log;

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

        $schedule->job(new Login)
            ->everyFiveMinutes()
            ->runInBackground();
        //$schedule->job(new GetTicket)->dailyAt('23:59');
        //$schedule->job(new GetTicketMultiProcess)->dailyAt('23:58');
        // $schedule->job(new GetTicketMultiProcess(20))
        //     ->everyMinute()
        //     ->runInBackground();
        // $schedule->job(new GetTicketMultiProcess(21))
        //     ->everyMinute()
        //     ->runInBackground();
        // $schedule->job(new GetTicketMultiProcess(20))
        //     ->everyMinute()
        //     ->runInBackground();
        // $schedule->job(new GetTicketMultiProcess(21))
        //     ->everyMinute()
        //     ->runInBackground();
        // $schedule->job(new GetTicketMultiProcess(20))
        //     ->everyMinute()
        //     ->runInBackground();
        // $schedule->job(new GetTicketMultiProcess(21))
        //     ->everyMinute()
        //     ->runInBackground();
        // $schedule->job(new GetOrderLists)->dailyAt('00:01');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
