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
    return view('welcome');
});
//Route::get('wxcode','WeChateController@wxcode');
//Route::get('wxtoken','WeChateController@wxtoken');

Route::group(['middleware' => ['web', 'wechat.oauth']], function () {
//    Route::get('/login', function () {
//        $user = session('wechat.oauth_user.default'); // 拿到授权用户资料
//
//        dd($user);
//    });
    Route::get('login','WeChatController@login')->name('login');

});
