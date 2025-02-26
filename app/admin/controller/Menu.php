<?php

namespace app\admin\controller;

use app\admin\model\SysMenu;
use app\admin\model\SysRole;
use app\BaseController;
use app\common\JsonResponse;
use app\Request;
use think\facade\Db;

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

    /**
     * 保存菜单
     */
    public function save() {
        $data = input();
        if($data['pid']) {
            $parent = SysMenu::find($data['pid']);
            if(!$parent) {
                return JsonResponse::error('父级菜单不存在');
            }
        } else {
            $data['pid'] = 0;
        }
        try{
            Db::startTrans();
            if (isset($data['id']) && $data['id']) {
                SysMenu::update($data);
            } else {
                SysMenu::create($data);
            }
            Db::commit();
        }catch (\Exception $e) {
            Db::rollback();
            return JsonResponse::error($e->getMessage());
        }
        return JsonResponse::success();
    }
    public function delete() {
        $id = input('id');
        if(!$id) {
            return JsonResponse::error('参数错误');
        }
        if(SysMenu::where('pid', $id)->find()) {
            return JsonResponse::error('存在子菜单，不能删除');
        }
        try {
            Db::startTrans();
            SysMenu::destroy($id);
            Db::commit();
        }catch (\Exception $e) {
            Db::rollback();
            return JsonResponse::error($e->getMessage());
        }
        return JsonResponse::success();
    }

}