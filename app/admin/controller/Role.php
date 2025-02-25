<?php

namespace app\admin\controller;

use app\admin\model\SysRole;
use app\admin\model\SysUser;
use app\BaseController;
use app\common\JsonResponse;
use think\facade\Db;

class Role extends BaseController
{
    /**
     * 角色列表页
     */
    public function index()
    {
        return view();
    }

    /**
     * 获取全部数据
     */
    public function list() {
        $roles = SysRole::select();
        return JsonResponse::success($roles);
    }

    /**
     * 获取角色列表
     */
    public function page()
    {
        $page = input('page', 1);
        $limit = input('limit', 10);
        $name = input('name', '');
        $code = input('code', '');

        $where = [];
        if ($name) {
            $where[] = ['name', 'like', "%{$name}%"];
        }
        if ($code) {
            $where[] = ['code', 'like', "%{$code}%"];
        }

        $count = SysRole::where($where)->count();
        $list = SysRole::where($where)
            ->page($page, $limit)
            ->order('id', 'desc')
            ->select();

        return JsonResponse::paginate($list, $count);
    }

    /**
     * 保存角色
     */
    public function save()
    {
        $data = input();

        try {
            Db::startTrans();
            if (isset($data['id']) && $data['id']) {
                SysRole::update($data);
            } else {
                SysRole::create($data);
            }
            Db::commit();
            return JsonResponse::success();
        } catch (\Exception $e) {
            Db::rollback();
            return JsonResponse::error($e->getMessage());
        }
    }

    /**
     * 删除角色
     */
    public function delete()
    {
        $id = input('id');
        if (!$id) {
            return JsonResponse::error('参数错误');
        }

        try {
            Db::startTrans();
            // 删除角色
            SysRole::destroy($id);
            // 删除角色-菜单关联
            Db::name('sys_role_menu')->where('role_id', $id)->delete();
            // 删除角色-用户关联
            Db::name('sys_user_role')->where('role_id', $id)->delete();
            Db::commit();
            return JsonResponse::success();
        } catch (\Exception $e) {
            Db::rollback();
            return JsonResponse::error($e->getMessage());
        }
    }
}