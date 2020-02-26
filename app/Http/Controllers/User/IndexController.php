<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UserModel;

class IndexController extends Controller
{

    public function reg()
    {
        return view('user.reg');
    }

    public function regDo(Request $request)
    {
        echo "<pre>";print_r($request->input());echo "</pre>";

        $pass1 = $request->input('pass1');
        $pass2 = $request->input('pass2');

        if($pass1 != $pass2){
            echo "两次输入的密码不一致";die;
        }

        $pass = password_hash($pass1,PASSWORD_BCRYPT);

        //入库
        $user_data = [
            'user_name' => $request->input('u_name'),
            'email' => $request->input('u_email'),
            'mobile' => $request->input('u_mobile'),
            'pass'  => $pass
        ];

        $uid = UserModel::insertGetId($user_data);

        if($uid > 0){
            echo "注册成功";
        }else{
            echo "注册失败";
        }

    }
}
