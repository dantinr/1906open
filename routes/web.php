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



Route::get('/github','GithubController@index'); ##GITHUB登录
Route::get('/github/callback2','GithubController@callback2'); ##用户授权回跳的页面
Route::get('/github/aaa','GithubController@callback2'); ##用户授权回跳的页面



Route::get('/user/reg','User\IndexController@reg');      //用户注册
Route::post('/user/reg','User\IndexController@regDo');     //用户注册

Route::get('/user/login','User\IndexController@login');     //用户登录
Route::post('/user/login','User\IndexController@loginDo');     //用户登录

Route::get('/user/center','User\IndexController@center');     //个人中心


## 外部调用接口
Route::get('/getAccessToken','User\IndexController@getAccessToken');    //获取accessToken


//接口需要access_token验证
Route::get('/api/test','Api\IndexController@test');
Route::get('/api/userinfo','Api\IndexController@userInfo');



##github回调
Route::get('/github/callback','OauthController@githubCallback');
