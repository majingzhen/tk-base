<?php

namespace app\admin\model;

use think\Model;

class SysUser extends Model
{
    // 关联角色
    public function roles()
    {
        return $this->belongsToMany(SysRole::class, 'sys_user_role', 'role_id', 'user_id');
    }
}