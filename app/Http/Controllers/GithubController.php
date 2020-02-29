<?php

namespace App\Http\Controllers;

use App\Model\UserModel;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Model\GithubUserModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redis;

class GithubController extends Controller
{
    //


    public function index()
    {
        return view('github.index');        // resources/views/github/index.blade.php
    }


    public function callback2()
    {

        $client = new Client();
        //echo "<pre>";print_r($_GET);echo "</pre>";

        // 获取code
        $code = $_GET['code'];          // github给我们的code

        // 用code 去github接口 获取 access_token
        $uri = 'https://github.com/login/oauth/access_token';

        $response = $client->request("POST",$uri,[

            // 携带 HTTP headers
            'headers'   => [
                'Accept'    => 'application/json'
            ],

            'form_params'   => [
                'client_id'         =>  env('GITHUB_CLIENT_ID'),
                'client_secret'     =>  env('GITHUB_CLIENT_SECRET'),
                'code'              =>  $code
            ]
        ]);

        //access_token 在响应的数据中
        $body = $response->getBody();
        //echo $body;echo '<hr>';
        $info = json_decode($body,true);

        $access_token = $info['access_token'];

        // 使用aceess_token 获取用户信息
        $uri = 'https://api.github.com/user';
        $response = $client->request('GET',$uri,[
            'headers'   => [
                'Authorization' => "token ".$access_token
            ]
        ]);

        $user_info = json_decode($response->getBody(),true);
        //echo "<pre>";print_r($user_info);echo "</pre>";

        //判断用户是否已存在，如果是新用户则将用户信息入库保存
        $u = GithubUserModel::where(['github_id'=>$user_info['id']])->first();
        $uid = $u->uid;
        if($u){     //用户存在
            //echo "欢迎回来";echo "<br>";
        }else{

            //在用户主表中记录用户信息
            $u_data = [
                'email' => $user_info['email'],
            ];
            $uid = UserModel::insertGetId($u_data);     //生成主表uid，关联 github用户表


            //在github_user表中记录用户信息
            $github_user_info = [
                'uid'           => $uid,
                'github_id'     => $user_info['id'],
                'location'      => $user_info['location'],
                'email'         => $user_info['email'],
            ];

            $gid = GithubUserModel::insertGetId($github_user_info);


            if($gid > 0){

            }else{

            }
        }



        //执行登录逻辑
        $token = Str::random(16);       //生成token，返回给客户端
        Cookie::queue('token',$token,60);

        //将token 保存至Redis中
        $redis_h_token = 'h:token:'.$token;     // h:token:d3GdX4WBe0BIFrv4

        $login_info = [
            'uid'           => $uid,
            'login_time'    => time()
        ];

        Redis::hMset($redis_h_token,$login_info);
        Redis::expire($redis_h_token,60*60);



        header("refresh:2;url=/user/center");
        echo "登录成功，正在跳转至个人中心";

    }
}
