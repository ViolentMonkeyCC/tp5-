<?php

namespace app\admin\model;

use think\Model;

class Category extends Model
{
    //指定当前模型的主键字段
    protected $pk = "cat_id";
    //时间戳自动维护
    protected $autoWriteTimestamp = true;

    //处理无限极递归数据
    public function getSonsCats($data, $pid = 0, $level = 1){
        //取出数据,进行处理
        static $result = [];    //静态变量,只初始化一次
        foreach ($data as $v) {
            if ($v['pid'] == $pid) {
                $v['level'] = $level;//加一个层级
                $result[] = $v; //存放在result中
                //递归调用查找子孙
                $this->getSonsCats($data, $v['cat_id'], $level+1);
            }
        }
        return $result;
    }
}
