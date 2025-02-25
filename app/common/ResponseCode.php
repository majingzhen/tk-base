<?php
namespace app\common;

class ResponseCode
{
    const SUCCESS = 1;
    const ERROR = 0;
    const UNAUTHORIZED = -1;
    
    // 系统级别错误码
    const SYSTEM_ERROR = 500;
    const PARAM_ERROR = 400;
    const NOT_FOUND = 404;
}