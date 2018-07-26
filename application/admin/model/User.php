<?php

namespace app\admin\model;

use think\Model;

class User extends Model
{
    public function checkUser($username, $password) {
        $where = [
            'username' => $username,
            'password' => md5($password.config('password_salt')),
        ];
        $userInfo = $this->where($where)->find();
        if ($userInfo) {    //$userInfo 是数据对象
            //验证成功, 将数据库读取出来的数据存储到session中
            session('user_id', $userInfo['user_id']);
            session('username', $userInfo['username']);
            return true;
        }else {
            return false;
        }
    }
}
