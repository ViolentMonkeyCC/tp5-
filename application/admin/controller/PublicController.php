<?php

namespace app\admin\controller;

use think\Controller;

class PublicController extends Controller{
    //登陆页面
    public function login() {
        return $this->fetch();
    }
}