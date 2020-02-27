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

    //echo "open.1906.com";echo "<br>";
    //echo date("Y-m-d H:i:s");echo "<br>";
    return view('welcome');
});



Route::get('/user/reg','User\IndexController@reg');      //用户注册
Route::post('/user/reg','User\IndexController@regDo');     //用户注册

Route::get('/user/login','User\IndexController@login');     //用户登录
Route::post('/user/login','User\IndexController@loginDo');     //用户登录

Route::get('/user/center','User\IndexController@center');     //个人中心
