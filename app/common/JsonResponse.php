<?php
namespace app\common;

class JsonResponse
{
    /**
     * 成功响应
     * @param mixed $data 响应数据
     * @param string $msg 响应信息
     * @return \think\Response
     */
    public static function success($data = null, string $msg = 'success')
    {
        return json([
            'code' => ResponseCode::SUCCESS,
            'msg'  => $msg,
            'data' => $data
        ]);
    }

    /**
     * 错误响应
     * @param string $msg 错误信息
     * @param int $code 错误码
     * @param mixed $data 响应数据
     * @return \think\Response
     */
    public static function error(string $msg = 'error', int $code = ResponseCode::ERROR, $data = null)
    {
        return json([
            'code' => $code,
            'msg'  => $msg,
            'data' => $data
        ]);
    }

    /**
     * 未授权响应
     * @param string $msg 错误信息
     * @return \think\Response
     */
    public static function unauthorized(string $msg = '请先登录')
    {
        return json([
            'code' => ResponseCode::UNAUTHORIZED,
            'msg'  => $msg,
            'data' => null
        ]);
    }

    /**
     * 分页列表响应
     * @param mixed $data 响应数据
     * @param int $total 数据总数
     * @param int $page 当前页码
     * @param int $pageSize 每页条数
     * @param string $msg 响应信息
     * @return \think\Response
     */
    public static function paginate($data, int $total, string $msg = 'success')
    {
        return json([
            'code' => ResponseCode::SUCCESS,
            'msg'  => $msg,
            'count' => $total,
            'data' => $data
        ]);
    }
}