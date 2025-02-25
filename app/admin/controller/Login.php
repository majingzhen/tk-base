<?php

namespace app\admin\controller;

use app\admin\model\SysUser;
use app\BaseController;
use app\common\JsonResponse;
use think\facade\View;

class Login extends BaseController
{
    public function index()
    {
        return View::fetch('login');
    }

    public function check()
    {
        if (!request()->isPost()) {
            return JsonResponse::error("非法请求");
        }

        $data = request()->post();
        
        // 验证码校验
        if (!captcha_check($data['captcha'])) {
            return JsonResponse::error('验证码错误');
        }

        $user = SysUser::where("username", $data['username'])->find();
        if (!$user || !password_verify($data['password'], $user->password)) {
            return JsonResponse::error('账号或密码错误');
        }
        if ($user->status != 1) {
            return JsonResponse::error('账号已被禁用');
        }
        // 保存登录时间
        $user->login_time = date('Y-m-d H:i:s');
        $user->login_ip = request()->ip();
        $user->save();
        session("admin_id", $user->id);
        session("admin_user", $user);
        return JsonResponse::success();
    }

    public function logout()
    {
        session(null);
        return redirect('/admin/login');
    }
}