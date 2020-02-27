<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserModel extends Model
{
    protected $table = 'p_users';


    /**
     * 生成APPID 规则 根据用户名 + 时间戳 + 随机数 进行md5
     */
    public static function gernerateAppid($user_name)
    {
        return 'ln'. substr(md5($user_name.time() . mt_rand(111111,999999)),5,14);
    }

    /**
     * 生成 APP SECRET
     */
    public static function generateSecret()
    {
        return Str::random(32);
    }
}
