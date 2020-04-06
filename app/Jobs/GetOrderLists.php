<?php

namespace App\Jobs;

use App\Http\Services\OderListService;
use App\Http\Services\SendMessageService;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetOrderLists
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
        $order_list_service = new OderListService;
        $send_message_service = new SendMessageService;

        $lists = $order_list_service->getOrderLists();

        $send_message = '';
        foreach ($lists as $list) {
            $send_message .= "日期:{$list['order_play_date']}" . PHP_EOL;
            $send_message .= "時間:{$list['order_time']}" . PHP_EOL;
            $send_message .= "場地:{$list['order_item']}" . PHP_EOL;
            $send_message .= "狀態:{$list['order_status']}" . PHP_EOL;
            $send_message .= PHP_EOL . PHP_EOL;
        }
        if (!$send_message) {
            $send_message = '沒搶到場地';
        }

        $send_message_service->sendMessage($send_message);

    }
}
