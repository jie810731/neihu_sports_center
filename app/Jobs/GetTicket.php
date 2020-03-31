<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Log;

class GetTicket implements ShouldQueue
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
            return;
        }

        //24小時
        $times = [6, 7];

        $sections = [88, 87];

        $get_ticket_date = date("Y/m/d", mktime(0, 0, 0, date("m"), date("d") + 8, date("Y")));
        $get_ticket_date = '2020/04/07';
        $can_start_get_ticket_time = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));

        $end_get_ticket_time = date("Y-m-d H:i:s", mktime(0, 0, 10, date("m"), date("d") + 1, date("Y")));

        $is_can_get_ticket = true;

        //echo ('going to start loop' . PHP_EOL);

        //while ($is_can_get_ticket) {
        $now = date("Y-m-d H:i:s", strtotime('now'));

        //if ($now >= $can_start_get_ticket_time) {

        foreach ($sections as $section) {
            foreach ($times as $time) {
                //echo ("post time = {$time} " . PHP_EOL);

                $this->postTicket($cookie, $get_ticket_date, $time, $section);

                //echo ("post time = {$time} end " . PHP_EOL);
            }
        }

        //} else {
        //echo ("time  not yet now = {$now}" . PHP_EOL);
        //}

        if ($now > $end_get_ticket_time) {
            // echo ('over time' . PHP_EOL);
            $is_can_get_ticket = false;
        } else {
            //echo ("continue loop" . PHP_EOL);
        }

        //}
        //echo ('loop end' . PHP_EOL);
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
