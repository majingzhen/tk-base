<?php

namespace app\admin\controller;

use app\BaseController;
use think\facade\View;

class Index extends BaseController
{
    public function index()
    {
        // 指定完整的模板路径
        return view('index');
    }

    public function welcome()
    {
        return view('welcome');
    }
}
