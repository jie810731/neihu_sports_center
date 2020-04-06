<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Log;

class GetTicket
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('get ticket job start');

        $cookie = Cache::get('cookie');

        if (!$cookie) {
            Log::info('empty cookie');
            return;
        }

        //24小時
        $times = [20, 21];

        $sections = [87,88];

        $get_ticket_date = date("Y/m/d", mktime(0, 0, 0, date("m"), date("d") + 8, date("Y")));
        //$get_ticket_date = '2020/04/07';
        $can_start_get_ticket_time = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));

        $end_get_ticket_time = date("Y-m-d H:i:s", mktime(0, 0, 10, date("m"), date("d") + 1, date("Y")));

        $is_can_get_ticket = true;

        while ($is_can_get_ticket) {
            $now = date("Y-m-d H:i:s", strtotime('now'));

            if ($now >= $can_start_get_ticket_time) {

                foreach ($sections as $section) {
                    foreach ($times as $time) {
                        Log::info("post time = {$time} ");

                        $this->postTicket($cookie, $get_ticket_date, $time, $section);

                        Log::info("post time = {$time} end ");
                    }
                }

            }

            if ($now > $end_get_ticket_time) {
                $is_can_get_ticket = false;
            } 
        }
    }

    public function postTicket($cookie, $get_ticket_date, $order_time, $section)
    {
        if (!$get_ticket_date || !$order_time) {
            return;
        }

        $jar = \GuzzleHttp\Cookie\CookieJar::fromArray(
            [
                'ASP.NET_SessionId' => $cookie,
            ],
            'scr.cyc.org.tw'
        );

        $query = [
            'module' => 'net_booking',
            'files' => 'booking_place',
            'StepFlag' => 25,
            'QPid' => $section,
            'QTime' => $order_time,
            'D' => $get_ticket_date,
        ];

        $client = new Client();

        $client->request('GET', 'https://scr.cyc.org.tw/tp12.aspx', [
            'query' => $query,
            'cookies' => $jar,
        ]);

    }

}
