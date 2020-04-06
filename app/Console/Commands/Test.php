<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\Login;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Jobs\GetTicket;
use App\Jobs\GetOrderLists;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Login::dispatch();
        //GetTicket::dispatch();
        GetOrderLists::dispatch();
        
    }
}
