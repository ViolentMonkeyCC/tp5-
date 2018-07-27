<?php

namespace app\admin\validate;

use think\Validate;

class CategoryValidate extends Validate
{
    //1.验证规则
    protected $rule = [
        'cat_name' => 'require|unique:category',
        'pid' => 'require',
    ];

    //2.验证不通过提示信息

    protected $message = [
        'cat_name.require' => '分类名不能为空!',
        'cat_name.unique' => '分类名已存在!',
        'pid.require' => '请选择父分类!',
    ];
    //3.验证场景
    protected $scene = [
        'add' => ['cat_name', 'pid'],
        'upd' => ['cat_name' => 'require', 'pid'],
    ];
}