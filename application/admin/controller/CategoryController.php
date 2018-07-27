<?php

namespace app\admin\controller;

use app\admin\model\Article;
use app\admin\model\Category;
use think\Controller;
use think\Request;
use app\admin\validate\CategoryValidate;

class CategoryController extends Controller
{
    //分类删除页
    public function ajaxDel(){
        //1.判断是否是ajax提交
        if (request()->isAjax()) {
            //2.接受数据
            $cat_id = input('cat_id');

            //3.判断当前分类下是否有子分类
            if (Category::where(['pid' => $cat_id])->find()) {
                //说明有子分类
                $response = ['code' => '-1', 'message' => '当前分类下有子分类,无法删除!'];
                return json($response);die;
            }

            //4.判断当前分类下是否有文章
            if (Article::where(['cat_id' => $cat_id])->find()) {
                //说明分类下有文章
                $response = ['code' => '-2', 'message' => '当前分类下有文章,无法删除!'];
                return json($response);die;
            }

            //5.判断完成,没有文章,没有子类,放心删除,但要判断是否删除成功
            if (Category::destroy($cat_id)) {//删除成功!
                $response = ['code' => '200', 'message' => '删除成功!'];
                return json($response);
            }else {                             //删除失败!
                $response = ['code' => '-3', 'message' => '删除失败!'];
                return json($response);
            }

        }
    }


    //分类编辑页
    public function upd(){
        $catModel = new Category();
        //1.判断是否是post提交的数据
        if (request()->isPost()) {
            //2.接收数据
            $postData = input('post.');
            //3.验证数据( 使用验证器进行验证 )
            $result = $this->validate($postData, 'CategoryValidate.upd', [], true);
            if ($result !== true) {
                $this->error(implode(',', $result));
            }
            //4.判断是否入库成功
            if ($catModel->update($postData)) {
                $this->success('编辑成功!', url('admin/category/index'));
            }else {
                $this->error('编辑失败!');
            }
        }

        //渲染数据
        //接收get提交的数据
        $cat_id = input('cat_id');
        $catData = $catModel->find($cat_id);

        $data = $catModel->select()->toArray();
        $cats = $catModel->getSonsCats($data);
//        halt($cats);
        return $this->fetch('',[
            'catData' => $catData,
            'cats' => $cats,
        ]);
    }


    //分类列表页
    public function index(){
        $catModel = new Category();
        $cats = $catModel
            ->field('t1.*, t2.cat_name p_name')
            ->alias('t1')
            ->join('category t2', 't1.pid=t2.cat_id', 'left')
            ->select()
            ->toArray();
//        $cats = $catModel->getSonsCats($data);
        return $this->fetch('', ['cats' => $cats]);
    }

    //分类添加页
    public function add()
    {
        $cateModel = new Category();
        //1.判断是否是post提交
        if (request()->isPost()) {
            //2.接收数据
            $postData = input('post.');

            //3.验证数据
            /*$rule = [
                'cat_name' => 'require|unique:category',
                'pid' => 'require',
            ];

            $message = [
                'cat_name.require' => '分类名不能为空!',
                'cat_name.unique' => '分类名已存在!',
                'pid.require' => '请选择父分类!',
            ];

            $validate = new Validate($rule, $message);
            if (!$validate->batch()->check($postData)) {    //验证不通过
                $this->error(implode(',', $validate->getError()));
            }*/
            //使用验证器进行验证
            $result = $this->validate($postData, 'CategoryValidate.add', [], true);
            //判断验证结果,验证成功( true ),验证失败( 错误提示信息的数组 )
            if ($result !== true) {//验证失败
                $this->error(implode(',', $result));
            }

            //4.判断入库是否成功
            if ($cateModel->save($postData)) {
                $this->success('添加成功!', url('admin/category/index'));
            }else {
                $this->error('添加失败!');
            }
        }
        
        #渲染数据
        $data = $cateModel->select()->toArray();
        //对分类数据进行无限极递归处理
        $cats = $cateModel->getSonscats($data);
        return $this->fetch('', ['cats' => $cats]);
    }

}
