<?php

namespace app\admin\controller;

use app\admin\model\SysUser;
use app\BaseController;
use app\common\JsonResponse;
use think\facade\Request;

class User extends BaseController
{
    public function index()
    {
        return view();
    }

    /**
     * 用户列表
     */
    public function list()
    {
        $page = (int)Request::param('page', 1);
        $limit = (int)Request::param('limit', 10);
        $username = Request::param('username', '');
        $where = [];
        if ($username) {
            $where[] = ['username', 'like', "%{$username}%"];
        }

        $list = SysUser::where($where)
            ->order('id', 'desc')
            ->paginate([
                'list_rows' => $limit,
                'page' => $page
            ]);

        return JsonResponse::paginate($list->items(),$list->total());
    }

    /**
     * 保存用户
     */
    public function save()
    {
        $data = Request::post();
        
        try {
            if (isset($data['id']) && $data['id']) {
                $user = SysUser::find($data['id']);
                if (!$user) {
                    return JsonResponse::error('用户不存在');
                }
                
                // 如果密码为空，不更新密码
                if (empty($data['password'])) {
                    unset($data['password']);
                } else {
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                }
                
                $user->save($data);
            } else {
                if (empty($data['password'])) {
                    return JsonResponse::error('密码不能为空');
                }
                
                $exists = SysUser::where('username', $data['username'])->find();
                if ($exists) {
                    return JsonResponse::error('用户名已存在');
                }
                
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                SysUser::create($data);
            }
            return JsonResponse::success();
        } catch (\Exception $e) {
            return JsonResponse::error('保存失败：' . $e->getMessage());
        }
    }

    /**
     * 删除用户
     */
    public function delete()
    {
        $id = Request::param('id');
        if (!$id) {
            return JsonResponse::error('参数错误');
        }
        
        try {
            SysUser::destroy($id);
            return JsonResponse::success();
        } catch (\Exception $e) {
            return JsonResponse::error('删除失败：' . $e->getMessage());
        }
    }
}