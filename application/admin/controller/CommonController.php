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

    //图片( 生成缩略图 ) 上传
    public function uploadImg($fileName){
        //判断是否有文件上传
        if ($file = request()->file($fileName)) {
            //定义上传文件的目录(./相对于入口文件index.php)
            $uploadDir = './upload/';
            //定义文件上传的验证规则
            $condition = [
                'size' => 1024*1024*10,//10M
                'ext' => 'png,jpg,gif,jpeg',//允许上传的格式
            ];
            //验证并进行文件上传
            $info = $file->validate($condition)->move($uploadDir);
            //判断是否上传成功
            if ($info) {
                //成功,获取上传的目录文件信息, 用于存储到数据库中
                $ori_img = $info->getSaveName();

                //生成缩略图
                $image = \think\Image::open('./'.$uploadDir.'/'.$ori_img);
                // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
                $oriPath = explode('\\',$ori_img);
                $ori_img = $oriPath[0]. '/' .$oriPath[1];//原图路径
                $thumb_img = $oriPath[0].'/thumb_'.$oriPath[1];//缩略图路径
                $image->thumb(100, 100, 2)->save('./'.$uploadDir.'/'.$thumb_img);
                return ['ori_img' => $ori_img, 'thumb_img' => $thumb_img];

            }else {
                //上传失败, 提示上传失败的错误信息
                $this->error($file->getError());
            }
        }
    }
}