<?php

namespace App\Common\Unit;

/**
 * Hash密码加密辅助类
 * Class Hash
 * @package App\Common\Unit
 */
class Hash
{
    /**
     * 创建密码
     * @param string $value
     * @return false|string|null
     */
    public static function make(string $value)
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }

    /**
     * 检查
     * @param string $value
     * @param string $hashedValue
     * @return bool
     */
    public static function check(string $value, string $hashedValue)
    {
        return password_verify($value, $hashedValue);
    }
}
