<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

/*return [
   __pattern__' => [
       'name' => '\w+',
   ],
   '[hello]'     => [
       ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
       ':name' => ['index/hello', ['method' => 'post']],
   ],

];*/

use think\Route;

//定义路由规则
/*Route::get('/', 'index/index/index');
Route::get('login/[:id]', 'index/index/login');//[:id]可省参数*/
//测试路由
Route::get('/test/[:name]', 'admin/test/test');
Route::get('/dbtest', 'admin/test/dbtest');
Route::get('/cattest', 'admin/test/cattest');
Route::get('/curdtest', 'admin/test/curdtest');


//后台首页视图
Route::get('/','admin/index/index');
Route::get('/top', 'admin/index/top');//顶部视图
Route::get('/left', 'admin/index/left');//左边视图
Route::get('/main', 'admin/index/main');//主体视图
Route::get('/login', 'admin/public/login');//登陆页面
Route::post('/login', 'admin/public/login');//登陆页面
Route::get('/logout', 'admin/public/logout');//退出页面
Route::group('admin', function () {
    //分类
    Route::get('/category/add', 'admin/category/add');//分类添加页面
    Route::post('/category/add', 'admin/category/add');//分类添加页面
    Route::get('/category/index', 'admin/category/index');//分类列表页面
    Route::get('/category/upd', 'admin/category/upd');//分类编辑页面
    Route::post('/category/upd', 'admin/category/upd');//分类编辑页面
    Route::get('/category/ajaxDel', 'admin/category/ajaxDel');//分类删除页面

    //文章
    Route::get('/article/index', 'admin/article/index');//文章列表页
    Route::get('/article/add', 'admin/article/add');//文章添加页
    Route::post('/article/add', 'admin/article/add');//文章添加页
    Route::get('/article/ajaxDel', 'admin/article/ajaxDel');//文章删除页

});