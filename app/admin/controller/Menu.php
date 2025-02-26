<?php

namespace app\admin\controller;

use app\admin\model\SysMenu;
use app\BaseController;
use app\common\JsonResponse;

class Menu extends BaseController
{
    /**
     * 菜单列表页
     */
    public function index()
    {
        return view();
    }

    public function list()
    {
        $menus = SysMenu::select();
        $menuTree = $this->buildMenuTree($menus);
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