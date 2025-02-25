<?php

namespace app\admin\controller;

use app\admin\model\SysUser;
use app\BaseController;
use app\common\JsonResponse;

class System extends BaseController
{
    /**
     * 获取当前登录用户
     * @return \think\Response
     */
    public function getLoginUser() {
        $user = session("admin_user");
        return JsonResponse::success($user);
    }

    /**
     * 获取当前用户所拥有的菜单
     * @return void
     */
    public function getMenu() {
        $user = SysUser::with(['roles.menus'])->find(session("admin_id"));
        if(!$user) {
            return JsonResponse::error('用户不存在!');
        }
        $menus = [];
        foreach ($user->roles as $role) {
            foreach ($role->menus as $menu) {
                if ($menu['menu_type'] == 0 && $menu['status'] == 1) {
                    $menus[$menu['id']] = $menu;
                }
            }
        }

        // 构建菜单树
        $menuTree = $this->buildMenuTree(array_values($menus));
        return JsonResponse::success($menuTree);
    }

    /**
     * 构建菜单树
     * @param array $menus
     * @param int $pid
     * @return array
     */
    private function buildMenuTree($menus, $pid = 0)
    {
        $tree = [];
        foreach ($menus as $menu) {
            if ($menu['pid'] == $pid) {
                $children = $this->buildMenuTree($menus, $menu['id']);
                if (!empty($children)) {
                    $menu['children'] = $children;
                }
                $tree[] = $menu;
            }
        }
        return $tree;
    }


}