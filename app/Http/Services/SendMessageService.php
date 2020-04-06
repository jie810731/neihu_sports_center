<?php

namespace App\Http\Services;
use GuzzleHttp\Client;

class SendMessageService
{
    public function sendMessage($message)
    {
        if(!$message){
          return ;  
        }

        $bot_token = env('TELEGRAM_BOT_TOKEN');
        $chat_id = env('TELEGRAM_CHAT_ID');


        $query = [
            'text' => $message,
            'chat_id' => $chat_id
        ];

        $client = new Client();

        $client->request('GET', "https://api.telegram.org/bot$bot_token/sendMessage?text=456&chat_id=-472180493", [
            'query' => $query,
        ]);
    }
}
