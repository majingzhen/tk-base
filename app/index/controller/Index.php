<?php

namespace app\index\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        return 'hello,ThinkPHP8';
    }

    public function hello($name = 'ThinkPHP8')
    {
        return 'hello,' . $name;
    }
}
