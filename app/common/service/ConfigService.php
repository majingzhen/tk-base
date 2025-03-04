<?php
namespace app\common\service;

use app\admin\model\SysConfig;
use think\facade\Cache;
use think\facade\Log;

class ConfigService
{
    // 缓存标识
    const CACHE_TAG = 'system:config';
    // 默认缓存时间（24小时）
    const CACHE_TIME = 86400;
    
    // 获取配置
    public static function get($key, $default = null)
    {
        try {
            // 先从缓存获取
            $value = Cache::get(self::CACHE_TAG . ':' . $key);
            if ($value !== null) {
                return $value;
            }
            
            // 从数据库获取
            $value = SysConfig::getConfigByKey($key);
            if ($value === null) {
                return $default;
            }

            // 写入缓存
            Cache::tag(self::CACHE_TAG)->set(self::CACHE_TAG . ':' . $key, $value, self::CACHE_TIME);
            
            return $value;
        } catch (\Exception $e) {
            Log::error("获取配置失败: {$key}, " . $e->getMessage());
            return $default;
        }
    }
    
    // 批量获取配置
    public static function getBatch(array $keys)
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = self::get($key);
        }
        return $result;
    }
    
    // 设置配置
    public static function set($key, $value)
    {
        try {
            // 更新数据库
            $result = SysConfig::setConfigByKey($key, $value);
            
            // 删除缓存
            Cache::delete(self::CACHE_TAG . ':' . $key);
            
            return $result;
        } catch (\Exception $e) {
            Log::error("设置配置失败: {$key}, " . $e->getMessage());
            return false;
        }
    }
    
    // 批量设置配置
    public static function setBatch(array $data)
    {
        foreach ($data as $key => $value) {
            self::set($key, $value);
        }
        return true;
    }
    
    // 删除配置
    public static function delete($key)
    {
        try {
            // 删除数据库记录
            $result = SysConfig::where('key', $key)->delete();
            
            // 删除缓存
            Cache::delete(self::CACHE_TAG . ':' . $key);
            
            return $result;
        } catch (\Exception $e) {
            Log::error("删除配置失败: {$key}, " . $e->getMessage());
            return false;
        }
    }
    
    // 清除配置缓存
    public static function clearCache()
    {
        return Cache::tag(self::CACHE_TAG)->clear();
    }
    
    // 刷新所有配置缓存
    public static function refreshCache()
    {
        try {
            // 清除所有缓存
            self::clearCache();
            
            // 重新加载所有配置到缓存
            $configs = SysConfig::select();
            foreach ($configs as $config) {
                Cache::tag(self::CACHE_TAG)->set(
                    self::CACHE_TAG . ':' . $config['key'], 
                    $config['value'], 
                    self::CACHE_TIME
                );
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error("刷新配置缓存失败: " . $e->getMessage());
            return false;
        }
    }
}