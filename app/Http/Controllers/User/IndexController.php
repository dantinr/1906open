<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UserModel;
use App\Model\AppModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redis;

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


        //用户名 手机号 Email 是否已存在

        // email格式 用户名 手机号 密码 格式验证


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
        $token = Str::random(16);       //生成token，返回给客户端
        Cookie::queue('token',$token,60);

        //将token 保存至Redis中
        $redis_h_token = 'h:token:'.$token;     // h:token:d3GdX4WBe0BIFrv4

        $login_info = [
            'uid'           => $u->id,
            'user_name'     => $u->user_name,
            'login_time'    => time()
        ];

        Redis::hMset($redis_h_token,$login_info);
        Redis::expire($redis_h_token,60*60);

        header("refresh:2;url=/user/center");
        echo "登录成功,正在跳转至个人中心";

    }


    /**
     * 个人中心
     */
    public function center()
    {

        $token = Cookie::get('token');

        if(empty($token)){
            echo "请先登录";die;
        }

        //echo "<pre>";print_r($token);echo "</pre>";

        //拿到token 拼接 redis key

        $redis_h_token = 'h:token:'.$token;     // h:token:d3GdX4WBe0BIFrv4
        //echo $key = $redis_h_token;

        $login_info = Redis::hgetAll($redis_h_token);

        //echo "<pre>";print_r($login_info);echo "</pre>";

        //获取 用户应用信息
        $app_info = AppModel::where(['uid'=>$login_info['uid']])->first()->toArray();

        //echo "<pre>";print_r($app_info);echo "</pre>";

        echo "欢迎来到个人中心：".$login_info['user_name'];echo "<br>";
        echo "APPID: ".$app_info['app_id'];echo "<br>";
        echo "APPSecret: ".$app_info['app_secret'];echo "<br>";





    }


    /**
     * 获取access_token,记录有效期
     */
    public function getAccessToken(Request $request)
    {
        $appid = $request->get('appid');
        $appsecret = $request->get('appsecret');

        if(empty($appsecret) || empty($appid))
        {
            echo "缺少参数";die;
        }

        //TODO 验证 appid 与 appsecret

        //echo 'appid: '.$appid;echo "<br>";
        //echo 'appsecret: '.$appsecret;echo "<br>";

        // 为用户生成accessToken，供后续接口调用
        $str = $appid . $appsecret . time() . mt_rand() . Str::random(16);
        //echo 'str:' .$str;echo "<br>";
        $access_token = sha1($str) . md5($str);
        //echo 'access_token: '.$access_token;echo "<br>";

        $redis_h_key = 'h:access_token:'.$access_token;     // redis hash
        //echo 'redis_key: '.$redis_h_key;

        $app_info = [
            'appid'     => $appid,
            'addtime'   => date('Y-m-d H:i:s')
        ];

        Redis::hMset($redis_h_key,$app_info);
        Redis::expire($redis_h_key,7200);

        $response = [
            'errno'         => 0,
            'access_token'  =>  $access_token,
            'expire'        => 7200
        ];

        return $response;


    }
}
