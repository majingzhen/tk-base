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

    public static function getConfigByKey($value)
    {
        return self::where('key', $value)->value('value');
    }

    public static function setConfigByKey($key, $value)
    {
        $config = self::where('key', $key)->find();
        if($config) {
            $config->value = $value;
            return $config->save();
        }
        return false;
    }
}