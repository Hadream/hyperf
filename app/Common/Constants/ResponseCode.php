<?php

namespace App\Common\Constants;

/**
 * 响应状态码枚举
 * Class ResponseCode
 * @package App\Common\Constants
 */
class ResponseCode
{
    const SUCCESS = 200;   // 接口处理成功
    const FAIL = 400;   // 接口处理失败
    const SERVER_ERROR = 500; // 系统异常
}
