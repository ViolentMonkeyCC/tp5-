<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use app\admin\model\Category;

class TestController extends Controller
{

    public function test(Request $request) {
        echo md5("abc123".config('IndexController.php')). '\n';
        dump( $request->module());
        dump( $request->controller());
        dump( $request->action());
        dump( $request->isPost());
        dump( $request->isAjax());
        dump( $request->isGet());

        echo "<hr />";
        //测试读取request上的数据
        dump(input('get.name', '', 'strtoupper' ));
    }

    //测试数据库的连接
    public function dbtest () {
        dump(Db::table('tp_category')->select());
    }

    //测试模型使用
    public function cattest() {
        /*$category = Category::get(2);
        dump($category->toArray());*/
        $category = new Category();
        dump($category->select()->toArray());
    }

    //测试数据库的CURD操作
    public function curdtest() {
        $catModel = new Category();
        //存储单条数据
        /*$data = [
            'cat_name' => '排球',
            'pid' => '1',
        ];
        dump($catModel->save($data));   //存储单条数据
        echo $catModel->cat_id; //获得自增后的主键字段的值*/

        //存储多条数据
        $data = [
            ['cat_name' => '排球', 'pid' => '1',],
            ['cat_name' => '乒乓球', 'pid' => '1',],
        ];
        dump($catModel->saveAll($data));

    }

}
