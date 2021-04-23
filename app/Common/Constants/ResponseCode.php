<?php

namespace App\Common\Constants;

/**
 * 响应状态码枚举
 * Class ResponseCode
 * @package App\Common\Constants
 */
class ResponseCode
{
    const SUCCESS       = 200;   // 接口处理成功
    const FAIL          = 400;   // 接口处理失败
    const LOGIN_ERROR   = 401; // 请登陆
    const LOGIN_EXPIRED = 403; // 登陆过期
    const SERVER_ERROR  = 500; // 系统异常

    const LOGIN_ERROR_MSG   = '请登陆'; // 请登陆消息
    const LOGIN_EXPIRED_MSG = '登陆过期'; // 登陆过期消息
    const SERVER_ERROR_MSG  = '系统开小差了^_^请重试一下'; // 系统异常消息
}
