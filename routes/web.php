<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    for($index = 0 ;$index< 10 ;$index++){
        fopen(route('api-get-ticket',[
            'index'=>$index
            ]
        ), "r");
    }
   
    return view('welcome');
});
