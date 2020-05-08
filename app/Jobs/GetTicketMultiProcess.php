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
    protected $time;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($time)
    {
        $this->court_service = new CourtService;
        $this->cookie = Cache::get('cookie');
        $this->time = $time;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("start MultiProcess");
        $mutable = Carbon::now();
        $mid = '12:00:00';

        if(date('H:i:s') < $mid){
            $get_ticket_day = $mutable->add(7, 'day');
        }else{
            $get_ticket_day = $mutable->add(8, 'day');
        }

        $get_ticket_day = $get_ticket_day->isoFormat('YYYY/MM/DD');

        $can_start_get_ticket_time = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));

        $section = 87;
        
        $this->court_service->postCourt($this->cookie, $get_ticket_day, $this->time, $section);
        
    }
}
