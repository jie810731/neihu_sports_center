<?php

namespace App\Jobs;

use App\Http\Services\CourtService;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Log;

class GetTicketMultiProcess
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $court_service;
    protected $cookie;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->court_service = new CourtService;
        $this->cookie = Cache::get('cookie');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mutable = Carbon::now();
        $get_ticket_day = $mutable->add(8, 'day');
        $get_ticket_day = $get_ticket_day->isoFormat('YYYY/MM/DD');

        $can_start_get_ticket_time = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));

        $times = [20, 21];

        $sections = [88, 87];

        foreach ($sections as $section) {
            foreach ($times as $time) {
                for ($index = 0; $index < 1; $index++) {
                    $pid = pcntl_fork();

                    if ($pid == -1) {
                        Log::info('Fork ERROR');
                    } else if ($pid) {
                        //Parent process
                    } else {
                        try {
                            while (date("Y-m-d H:i:s", strtotime('now')) > $can_start_get_ticket_time) {
                                Log::info("multi process start section = {$section} time = {$time}");
                                //Log::info('cookie = '.$cookie);
                                $this->court_service->postCourt($this->cookie, $get_ticket_day, $time, $section);
                                Log::info("multi process end section = {$section} time = {$time}");
                            }
                        } catch (Exception $ex) {
                            Log::error($ex->getMessage());
                        }
                        break;
                    }
                }
            }
        }
    }
}
