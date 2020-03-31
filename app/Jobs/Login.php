<?php

namespace App\Jobs;

use App\Http\Services\LoginService;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Session;
use Log;

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
            $login_success = $login_service->login();

        } while (!$login_success);

        Session::put('cookie', $login_service->$cookie);

    }
}
