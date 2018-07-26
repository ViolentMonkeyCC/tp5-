<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class CommonController extends Controller
{
    public function _initialize(){
        if (!session('user_id')) {
            //没有则提示用户登录之后在进行操作
            $this->success('登录后再试', url('/login'));
        }
    }
}