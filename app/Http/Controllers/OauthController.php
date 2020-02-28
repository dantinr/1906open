<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class OauthController extends Controller
{

    public function githubCallback()
    {

        $client_id = '53c54c6b2f5bbbc2b684';
        $client_secret = 'b83b89d30c9935a9332aa7b50ad05ee4a48edfb6';
        // 获取code
        echo "<pre>";print_r($_GET);echo "</pre>";
        $code = $_GET['code'];          //获取code

        // 请求token
        $url = 'https://github.com/login/oauth/access_token';

        $client = new Client();

        $response = $client->request('POST',$url,[

            'headers'   =>[
              'Accept'  =>  'application/json'
            ],

           'form_params'    => [
               'client_id'      => $client_id,
               'client_secret'  => $client_secret,
               'code'           => $code,
           ]
        ]);

        $body = $response->getBody();   // {"access_token":"088a1d504f1ccac357512b8b5c3c303dd45cf773","token_type":"bearer","scope":""}
        echo 'body:'.$body;

        //取出 access_token
        $token_info = json_decode($body,true);


        //获取用户信息
        $url = 'https://api.github.com/user';

        $response = $client->request("GET",$url,[
           'headers'    => [
               'Authorization'  => "token ".$token_info['access_token']
           ]
        ]);

        $body = $response->getBody();
        echo '用户信息: '.$body;echo '<hr>';


        $arr = json_decode($body,true);
        echo "<pre>";print_r($arr);echo "</pre>";





    }
}
