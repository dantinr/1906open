<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UserModel;
use App\Model\AppModel;

class IndexController extends Controller
{

    public function reg()
    {
        return view('user.reg');
    }

    /**
     * 用户注册
     * @param Request $request
     */
    public function regDo(Request $request)
    {
        echo "<pre>";print_r($request->input());echo "</pre>";

        $pass1 = $request->input('pass1');
        $pass2 = $request->input('pass2');
        $user_name = $request->input('u_name');
        $email = $request->input('u_email');
        $mobile = $request->input('u_mobile');

        if($pass1 != $pass2){
            echo "两次输入的密码不一致";die;
        }

        $pass = password_hash($pass1,PASSWORD_BCRYPT);

        //入库
        $user_data = [
            'user_name' => $user_name,
            'email' => $email,
            'mobile' => $mobile,
            'pass'  => $pass,
        ];

        $uid = UserModel::insertGetId($user_data);

        if($uid > 0){
            echo "注册成功";echo "<br>";
        }else{
            echo "注册失败";echo "<br>";
        }

        // 为用户生成 APPID 与 SECRET
        $app_id = UserModel::gernerateAppid($user_name);
        $app_secret = UserModel::generateSecret();

        //写入 APP表中
        $app_info = [
            'uid'           => $uid,
            'app_id'        => $app_id,
            'app_secret'    => $app_secret,
        ];

        $id = AppModel::insertGetId($app_info);
        if($id > 0){
            echo "ok";echo "<br>";
        }else{
            echo "内部错误，请联系管理员";echo "<br>";
        }

        echo "用户APP_ID: ". $app_id;echo "<br>";
        echo "APP_SECRET: ". $app_secret;echo "<br>";

    }


    /**
     * 登录
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login()
    {
        return view('user.login');
    }

    /**
     * 登录
     * @param Request $request
     */
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
