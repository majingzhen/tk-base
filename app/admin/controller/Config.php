<?php
namespace app\admin\controller;

use app\admin\model\SysConfig;
use app\BaseController;
use app\common\JsonResponse;
use think\facade\Db;

class Config extends BaseController 
{
    /**
     * 配置列表页
     */
    public function index()
    {
        return view();
    }

    /**
     * 配置列表
     */
    public function page()
    {
        $page = input('page', 1);
        $limit = input('limit', 10);
        $tab = input('tab', '');
        $name = input('name', '');

        $where = [];
        if($name) {
            $where[] = ['name|key', 'like', "%{$name}%"];
        }
        if($tab) {
            $where[] = ['tab', '=', $tab];
        }

        $count = SysConfig::where($where)->count();
        $list = SysConfig::where($where)
            ->page($page, $limit)
            ->order('sort', 'asc')
            ->select();

        return JsonResponse::paginate($list, $count);
    }

    /**
     * 保存配置
     */
    public function save()
    {
        $data = input();
        
        try {
            Db::startTrans();
            if(isset($data['id']) && $data['id']) {
                SysConfig::update($data);
            } else {
                // 检查key是否重复
                if(SysConfig::where('key', $data['key'])->find()) {
                    return JsonResponse::error('配置键名已存在');
                }
                SysConfig::create($data);
            }
            Db::commit();
            return JsonResponse::success();
        } catch (\Exception $e) {
            Db::rollback();
            return JsonResponse::error($e->getMessage());
        }
    }

    /**
     * 删除配置
     */
    public function delete()
    {
        $id = input('id');
        if(!$id) {
            return JsonResponse::error('参数错误');
        }
        try {
            SysConfig::destroy($id);
            return JsonResponse::success();
        } catch (\Exception $e) {
            return JsonResponse::error($e->getMessage());
        }
    }
}