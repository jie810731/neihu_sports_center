<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('get-ticket', function (Request $request) {
    $index = $request->get('index');
    if($index % 2 == 0){
        sleep(3);
    }
    \Log::info('get-ticket start'.$index);

    // $order_date = $request->get('order_date');
    // $order_time = $request->get('order_time');
    // $section = $request->get('section');

    // $login_service = new \App\Http\Services\LoginService;
    // $court_service = new \App\Http\Services\CourtService;

    // $is_validated = !empty($order_date) || !empty($order_time) || !empty($section);

    // if (!$is_validated) {
    //     \Log::info('Miss parameter');
    //     return 'Miss parameter';
    // }

    // $cookie = \Illuminate\Support\Facades\Cache::get('cookie');

    // $login_service->login();
    // $court_service->postCourt($cookie, $order_date, $order_time, $section);

})->name('api-get-ticket');
