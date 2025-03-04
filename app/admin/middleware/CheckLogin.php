<?php
declare (strict_types = 1);

namespace app\admin\middleware;

use think\facade\Session;

class CheckLogin
{
    // 不需要登录验证的方法
    protected $excludeMethods = [
        'login/index',
        'login/check',
        'login/captcha',
        'config/getConfigByTab'
    ];

    public function handle($request, \Closure $next)
    {
        // 获取当前访问的控制器和方法
        $controller = strtolower($request->controller());
        $action = strtolower($request->action());
        $currentMethod = $controller . '/' . $action;

        // 如果当前访问的方法在排除列表中，直接通过
        if (in_array($currentMethod, $this->excludeMethods)) {
            return $next($request);
        }
        
        // 检查是否登录
        if (!Session::has('admin_id')) {
            // Ajax请求返回JSON
            if ($request->isAjax()) {
                return json(['code' => -1, 'msg' => '请先登录']);
            }
            
            // 检查是否在iframe中
            if ($request->header('sec-fetch-dest') === 'iframe') {
                return response('<script>top.location.href="' . (string)url('/admin/login') . '";</script>');
            }
            
            // 普通请求跳转到登录页
            return redirect((string)url('/admin/login'));
        }

        return $next($request);
    }
}