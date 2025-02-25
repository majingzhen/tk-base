<?php

namespace app\admin\model;

use think\Model;

class SysRole extends Model
{
    // 关联菜单
    public function menus()
    {
        return $this->belongsToMany(SysMenu::class, 'sys_role_menu', 'menu_id', 'role_id');
    }
}