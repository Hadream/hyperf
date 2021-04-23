<?php

namespace App\Common\Request\Http;

use App\Common\Request\BaseRequest;

/**
 * 权限控制器验证
 * Class AuthRequest
 * @package App\Request\Api
 */
class AuthRequest extends BaseRequest
{
    # 获取适用于请求的验证规则
    public function rules(): array
    {
        return [
            'mobile' => "required|regex:/^1[345789][0-9]{9}$/",
            'password' => 'required',
            'platform' => 'required|in:h5,ios,windows,mac,web',
        ];
    }

    # 获取已定义验证规则的错误消息
    public function messages(): array
    {
        return [
            'mobile.required' => '手机号必须',
            'mobile.regex' => '手机号格式错误',
            'password.required' => '请输入密码',
            'platform.required' => '参数错误',
            'platform.in' => '参数错误',
        ];
    }
}