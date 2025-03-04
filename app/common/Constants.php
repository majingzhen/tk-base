<?php
namespace app\common;

class Constants
{
    // 通用状态
    const STATUS_ENABLE = 1;        // 启用
    const STATUS_DISABLE = 0;       // 禁用
    const STATUS_DELETED = -1;      // 删除

    // 用户相关
    const USER_LOGIN_FAIL_MAX = 5;  // 最大登录失败次数
    const USER_ADMIN = 1;           // 管理员
    const USER_NORMAL = 2;          // 普通用户
    
    // 响应状态码
    const SUCCESS_CODE = 200;       // 成功状态码
    const ERROR_CODE = 500;         // 错误状态码
    
    // 时间格式
    const DATE_FORMAT = 'Y-m-d';
    const DATETIME_FORMAT = 'Y-m-d H:i:s';
    
    // 分页
    const DEFAULT_PAGE_SIZE = 10;   // 默认分页大小
}
