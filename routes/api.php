<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

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

Route::post('getEvent', 'ApiController@getEvent');

Route::post('uploadToServer', 'ApiController@uploadToServer');

Route::post('sendWhatsappMsg', 'ApiController@sendWhatsappMsg');

Route::post('sendSMS', 'ApiController@sendSMS');

Route::post('sendEmail', 'ApiController@sendEmail');

Route::get('test', function () {
    $data = array("msg" => "Test mail");
    Mail::send('user.mail', $data, function ($message) {
        $message->from(env('MAIL_USERNAME'), env('MAIL_USERNAME'));
        $message->to("smartdevpro001@gmail.com");
        $message->subject("test");
    });
    $time = strtotime("2010-01-12 14:02:17");
    $creation_date = new DateTime('@' . $time);
    return $creation_date->format('Y-m-d H:i:s');
});
