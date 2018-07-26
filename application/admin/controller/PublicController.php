<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\User;

class PublicController extends Controller{
    //登陆页面
    public function login(Request $request) {
        //判断是否是post提交
        if ($request->isPost()) {
            //接收参数
            $postDate = input('post.');

            //验证数据(后面实现)
            //调用模型方法checkUser ,检测用户名和密码
            $userModel = new User();
            $flag = $userModel->checkUser($postDate['username'], $postDate['password']);
            if ($flag) {
                //直接重定向到后台首页
                $this->redirect('/');
            } else {
                //提示用户密码或账号错误
                $this->error('用户密码或账号错误');
            }
        }


        return $this->fetch();
    }

    //退出页面
    public function logout() {
        //清除session数据
        //session('username', null); //清除其中某个session数据
        session(null);  //清除所有session数据
        $this->redirect('/login');
    }
}