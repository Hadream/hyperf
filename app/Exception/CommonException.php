<?php

namespace App\Exception;

use App\Common\Constants\ResponseCode;
use Hyperf\Server\Exception\ServerException;

/**
 * 异常类
 * Class CommonException
 * @package App\Exception
 */
class CommonException extends ServerException
{
    public function __construct($message = null, $code = ResponseCode::FAIL)
    {
        parent::__construct($message, $code);
    }
}