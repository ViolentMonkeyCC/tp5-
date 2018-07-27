<?php

namespace app\admin\controller;

use app\admin\model\Article;
use app\admin\model\Category;
use think\Controller;
use think\Request;
use app\admin\validate\ArticleValidate;

class ArticleController extends Controller
{
    //文章列表页
    public function index() {
        //展示数据, 查询数据
        $articleModel = new Article();
        $arts = $articleModel
            ->field('a1.article_id, a1.title, a1.cat_id, a1.thumb_img, a1.create_time, a1.update_time, c1.cat_name p_name')
            ->alias('a1')
            ->join('category c1', 'a1.cat_id = c1.cat_id', 'left')
            ->select()
            ->toArray();

        return $this->fetch('', ['arts' => $arts]);
    }

    //文章添加页
    public function add(){
        #数据入库
        //1.判断是否是post提交
        if (request()->isPost()) {
            //2.接收数据
            $postData = input('post.');

            //3.判断验证数据是否成功
            $result = $this->validate($postData, 'ArticleValidate.add', [], true);
            if ($result !== true) {
                $this->error(implode(',', $result));//验证失败,返回的是一个数组,error只返回字符串
            }

            //4.判断是否有文件上传
            if ($file = request()->file('image')) {
                //定义上传文件的目录(./相对于入口文件index.php)
                $uploadDir = './upload/';
                //定义文件上传的验证规则
                $condition = [
                    'size' => 1024*1024*2,//2M
                    'ext' => 'png,jpg,gif,jpeg',//允许上传的格式
                ];
                //验证并进行文件上传
                $info = $file->validate($condition)->move($uploadDir);
                //判断是否上传成功
                if ($info) {
                    //成功,获取上传的目录文件信息, 用于存储到数据库中
                    $postData['ori_img'] = $info->getSaveName();
                }else {
                    //上传失败, 提示上传失败的错误信息
                    $this->error($file->getError());
                }
            }
            //5.判读是否入库成功
            $articleModel = new Article();
            if ($articleModel->save($postData)) {
                //入库成功
                $this->success('添加成功!', url('admin/article/index'));
            }else {
                //入库失败
                $this->error('添加失败！');
            }


        }
        //无限极递归分类
        $catModel = new Category();
        $data = $catModel->select()->toArray();
        $cats = $catModel->getSonsCats($data);
        return $this->fetch('', ['cats' => $cats]);
    }

    //文章编辑页
    public function upd(){

    }

    //文章删除页
    public function ajaxDel(){
        //1.判断是否是ajax请求
        if (request()->isAjax()) {
            //2.接收数据,要删除的id
            $article_id= input('article_id');
            //3.直接进行删除文章(软删除会好一点)
            $articleModel = new Article();
            //判断是否删除成功
            if ($articleModel->where(['article_id' => $article_id])->delete()) {
                //删除成功
                $reponse = ['code' => '200', 'message' => '删除成功!'];
                return $reponse;die;
            }else {
                //删除失败
                $reponse = ['code' => '-1', 'message' => '删除失败!'];
                return $reponse;die;
            }
        }
    }

}
