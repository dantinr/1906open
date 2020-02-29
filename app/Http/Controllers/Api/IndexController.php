<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class IndexController extends Controller
{
    public function test(Request $request)
    {

        //验证token是否可用
        $token = $request->get('token');
        if(empty($token)){
            echo "授权失败 缺少 access_token";die;
        }

        $redis_h_token = 'h:access_token:'.$token;
        $data = Redis::hGetAll($redis_h_token);

        if(empty($data)){
            echo "授权失败，access_token 无效";echo '<hr>';die;
        }


        $data = [
            'user_name' => 'zhangsan',
            'time'      => date('Y-m-d H:i:s')
        ];

        return $data;
    }


    public function userInfo()
    {


        $data = [
            'user_name' => 'zhangsan',
            'email'     => 'zhangsan@qq.com'
        ];

        return $data;
    }

}
