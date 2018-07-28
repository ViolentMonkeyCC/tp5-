<?php

namespace app\admin\validate;

use think\Validate;

class ArticleValidate extends Validate
{
    //1.验证规则
    protected $rule = [
        'title' => 'require|unique:article',
        'cat_id' => 'require',
    ];

    //2.验证错误提示信息
    protected $message = [
        'title.require' => '文章标题不能为空!',
        'title.unique' => '文章标题已存在!',
        'cat_id.require' => '所属分类不能为空!',
    ];

    //3.验证场景
    protected $scene = [
        'add' => ['title', 'cat_id'],
        'upd' => ['title', 'cat_id'],
    ];
}