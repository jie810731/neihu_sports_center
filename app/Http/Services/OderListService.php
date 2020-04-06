<?php

namespace App\Http\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\DomCrawler\Crawler;

class OderListService
{
    public function getOrderLists()
    {
        $lists = [];
        $cookie = Cache::get('cookie');

        if (!$cookie) {
            return;
        }

        $jar = \GuzzleHttp\Cookie\CookieJar::fromArray(
            [
                'ASP.NET_SessionId' => $cookie,
            ],
            'scr.cyc.org.tw'
        );

        $search_date = date("Y/m/d");

        $query = [
            'Module' => 'member',
            'files' => 'orderx_mt',
            'C' => $search_date,
            'D' => $search_date,
        ];

        $client = new Client();

        $response = $client->request('GET', 'https://scr.cyc.org.tw/tp12.aspx', [
            'query' => $query,
            'cookies' => $jar,
        ]);

        $body = (string) $response->getBody();

        if (!$body) {
            return;
        }

        //$html = Storage::get('order_lists.html');

        $crawler = new Crawler($body);

        $tr_elements = $crawler->filterXPath('//*[@id="subform_List"]/table/tr[2]/td/table/tr[2]/td/table')->children();

        foreach ($tr_elements as $index => $tr) {
            if ($index == 0) {
                continue;
            }
            $span_elements = $tr->getElementsByTagName('span');

            $font = $span_elements->item(10)->getElementsByTagName('font');

            $td_object = [
                'order_date' => $span_elements->item(0)->nodeValue,
                'order_item' => $span_elements->item(3)->nodeValue,
                'order_play_date' => $span_elements->item(6)->nodeValue,
                'order_time' => $span_elements->item(8)->nodeValue,
                'order_status' => $font->item(0)->nodeValue,
            ];

            $lists[] = $td_object;

        }

        return $lists;

    }
};
