<?php

namespace App\Http\Services;

use GuzzleHttp\Client;
use Log;

class CourtService
{
    public function postCourt($cookie, $order_date, $order_time, $section)
    {
        if ($cookie) {
            return;
        }
        if (!$order_date || !$order_time) {
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
            'D' => $order_date,
        ];

        $client = new Client();

        $client->request('GET', 'https://scr.cyc.org.tw/tp12.aspx', [
            'query' => $query,
            'cookies' => $jar,
        ]);

    }
}
