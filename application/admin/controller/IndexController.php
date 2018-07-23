<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class IndexController extends Controller
{
    /**
     * 输出首页视图
     * @return mixed
     */
    public function index() {
        return $this->fetch();
    }

    /**
     * 输出顶部视图
     * @return mixed
     */
    public function top() {
        return $this->fetch();
    }

    /**
     * 输出左边视图
     * @return mixed
     */
    public function left() {
        return $this->fetch();
    }

    /**
     * 输出主体视图
     * @return mixed
     */
    public function main() {
        return $this->fetch();
    }
}
