<?php
namespace app\admin\model;

use think\Model;

class SysConfig extends Model
{
    // 设置表名
    protected $name = 'sys_config';
    
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    
    // 类型转换
    protected $type = [
        'status'    => 'integer',
        'sort'      => 'integer',
        'options'   => 'array',
    ];
    
    // 获取配置值
    public static function getConfig($key, $default = null)
    {
        $value = self::where('key', $key)->value('value');
        return $value !== null ? $value : $default;
    }
    
    // 设置配置值
    public static function setConfig($key, $value)
    {
        return self::where('key', $key)->update(['value' => $value]);
    }
}