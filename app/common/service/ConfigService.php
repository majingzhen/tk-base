<?php
namespace app\common\service;

use app\admin\model\SysConfig;
use think\facade\Cache;

class ConfigService
{
    // 缓存标识
    const CACHE_TAG = 'system:config';
    
    // 获取配置
    public static function get($key, $default = null)
    {
        // 先从缓存获取
        $value = Cache::get(self::CACHE_TAG . ':' . $key);
        if ($value !== null) {
            return $value;
        }
        
        // 从数据库获取
        $value = SysConfig::getConfig($key, $default);
        
        // 写入缓存
        Cache::tag(self::CACHE_TAG)->set(self::CACHE_TAG . ':' . $key, $value);
        
        return $value;
    }
    
    // 设置配置
    public static function set($key, $value)
    {
        // 更新数据库
        $result = SysConfig::setConfig($key, $value);
        
        // 删除缓存
        Cache::delete(self::CACHE_TAG . ':' . $key);
        
        return $result;
    }
    
    // 清除配置缓存
    public static function clearCache()
    {
        return Cache::tag(self::CACHE_TAG)->clear();
    }
}