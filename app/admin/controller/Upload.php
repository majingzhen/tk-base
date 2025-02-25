<?php

namespace app\admin\controller;

use app\BaseController;
use app\common\JsonResponse;
use think\facade\Filesystem;

class Upload extends BaseController
{
    public function upload()
    {
        try {
            // 获取上传的文件
            $file = request()->file('file');
            
            if (!$file) {
                return JsonResponse::error('未选中上传文件');
            }

            // 验证文件
            validate(['file'=>[
                'fileSize' => 2097152,  // 2MB = 2*1024*1024
                'fileExt'  => ['jpg', 'jpeg', 'png', 'gif'],
                'fileMime' => ['image/jpeg', 'image/png', 'image/gif'],
            ]])->check(['file' => $file]);

            // 上传到public/uploads目录
            $saveName = Filesystem::disk('public')->putFile('uploads', $file);
            
            if ($saveName) {
                // 返回可访问的URL路径
                $url = '/storage/' . str_replace('\\', '/', $saveName);
                return JsonResponse::success($url);
            } else {
                return JsonResponse::error('图片上传失败');
            }
        } catch (\Exception $e) {
            return JsonResponse::error($e->getMessage());
        }
    }
}