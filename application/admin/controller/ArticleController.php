<?php

namespace app\admin\controller;

use app\admin\model\Article;
use app\admin\model\Category;
use app\admin\validate\ArticleValidate;

class ArticleController extends CommonController
{
    //文章列表页
    public function index() {
        //展示数据, 查询数据
        $articleModel = new Article();
        $arts = $articleModel
            ->field('a1.article_id, a1.title, a1.cat_id, a1.thumb_img, a1.create_time, a1.update_time, c1.cat_name p_name')
            ->alias('a1')
            ->join('category c1', 'a1.cat_id = c1.cat_id', 'left')
            ->order('a1.article_id desc')
            ->paginate(3);

        return $this->fetch('', ['arts' => $arts, 'count' => $articleModel->count('article_id')]);
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

            //4.原图和缩略图生成及存储到要入库的数据中
            $imgPaths = $this->uploadImg('img');
            $postData['ori_img'] = $imgPaths['ori_img'];//原图路径
            $postData['thumb_img'] = $imgPaths['thumb_img'];//缩略图路径

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
        $articleModel = new Article();
        //1.判断是否是post提交
        if (request()->isPost()) {
            //2.接收数据
            $postData = input('post.');
            //3.判断验证用户输入数据是否成功
            $result = $this->validate($postData, 'ArticleValidate.upd', [], true);
            if ($result !== true) {
                $this->error(implode(',', $result));
            }

            //4.验证成功之后, 原图和缩略图生成及存储到要入库的数据中
            $imgPaths = $this->uploadImg('img');
            //判断用户有没有更新图片
            if ($imgPaths) {
                //用户更新了图片, 删除之前上传的图片
                //获取原来的图片路径,并删除
                $oldData = $articleModel->find($postData['article_id']);
                if ($oldData['ori_img']){   //如果原图存在,则进行删除
                    unlink('./upload/'. $oldData['ori_img']);//删除文件( 原图 )
                    unlink('./upload/'. $oldData['thumb_img']);//删除文件( 缩略图 )
                }
                $postData['ori_img'] = $imgPaths['ori_img'];//原图路径
                $postData['thumb_img'] = $imgPaths['thumb_img'];//缩略图路径
            }

            //5.判断是否入库成功
            if ($articleModel->update($postData)) {
                $this->success('编辑成功!', url('/admin/article/index'));
            }else {
                $this->error('编辑失败!');
            }
        }

        //接收数据id, 用于查询回显的数据
        $article_id = input('article_id');
        $artData = $articleModel->find($article_id);

        //无限极递归分类
        $catModel = new Category();
        $data = $catModel->select()->toArray();
        $cats = $catModel->getSonsCats($data);
        return $this->fetch('', ['cats' => $cats, 'artData' => $artData]);
    }

    //文章删除页
    public function ajaxDel(){
        //1.判断是否是ajax请求
        if (request()->isAjax()) {
            //2.接收数据,要删除的id
            $article_id= input('article_id');
            //3.直接进行删除文章(软删除会好一点)
            $articleModel = new Article();
            //删除原图和缩略图
            $imgpaths = $articleModel->find($article_id);
            if ($imgpaths['ori_img']) {
                unlink('./upload/'. $imgpaths['ori_img']);
                unlink('./upload/'. $imgpaths['thumb_img']);
            }
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

    //查看文章内容
    public function getContent(){
        //1.判断是否是ajax提交的数据
        if (request()->isAjax()) {
            //接收数据
            $article_id = input('article_id');
            //查询数据
            $content = Article::where(['article_id' => $article_id])->value('content');
            //返回数据
            return json(['content'=> $content]);
        }
    }

}
