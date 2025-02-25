<?php

namespace app\admin\model;

use think\Model;

class SysMenu extends Model
{

    // 赋予子权限
    public function children() {
        return $this->hasMany(SysMenu::class, 'pid');
    }
}