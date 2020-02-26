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


    /**
     * 登录
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login()
    {
        return view('user.login');
    }

    public function loginDo(Request $request)
    {
        //echo "<pre>";print_r($request->input());echo "</pre>";echo '<hr>';
        $name = $request->input('u_name');      // 可以使 user_name  Email  Mobile
        $pass = $request->input('pass');

        $u = UserModel::where(['user_name'=>$name])
            ->orWhere(['email'=>$name])
            ->orWhere(['mobile'=>$name])
            ->first();

        if($u == NULL){
            echo "用户不存在";
            die;
        }

        //验证密码
        if( !password_verify($pass,$u->pass) )
        {
            echo "密码不正确";
            die;
        }

        // 登录成功
        echo "登录成功,正在跳转至个人中心";
        
    }
}
