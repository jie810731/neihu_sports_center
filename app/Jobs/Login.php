<?php

namespace App\Jobs;

use App\Http\Services\LoginService;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Log;
use Exception;

class Login
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
        Log::info('login job start');

        $login_service = new LoginService;

        $login_success = false;
        do {

            try{
                $login_success = $login_service->login();
            }catch(Exception $ex){
                Log::error($ex->getMessage());
                $login_success = true;
            }
            

        } while (!$login_success);

        Cache::put('cookie', $login_service->cookie);

        Log::info('redis cookie = '.Cache::get('cookie'));

    }
}
