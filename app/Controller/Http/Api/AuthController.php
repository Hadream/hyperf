<?php

namespace App\Controller\Http\Api;

use App\Controller\Http\BaseController;
use App\Common\Request\Http\AuthRequest;
use App\Common\Service\User\UserService;
use App\Common\Middleware\AuthMiddleware;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Annotation\Middleware;

/**
 * 授权相关控制器
 * @Controller(prefix="api/v1/auth")
 * Class AuthController
 * @package App\Controller\Http\Api
 */
class AuthController extends BaseController
{
    /**
     * @Inject
     * @var UserService
     */
    private $userService;

    /**
     * 授权登录接口
     * @RequestMapping(path="login", methods="post")
     * @param AuthRequest $request
     * @return mixed
     */
    public function login(AuthRequest $request)
    {
        $params = $request->validated();
        $userInfo = $this->userService->login($params['mobile'], $params['password'], $params['platform']);
        if (!$userInfo) return $this->error('账号不存在或密码填写错误^_^');

        $jwtInfo = [
            'user_id' => $userInfo['id'],
            'platform' => $params['platform'],
        ];
        $token = $this->getToken($jwtInfo);

        return $this->success([
            'authorize' => [
                'token' => $token,
                'expires_in' => $this->getTTLToken()
            ],
            'user_info' => [
                'username' => $userInfo['username'],
                'avatar' => $userInfo['avatar'],
                'mobile' => $userInfo['mobile'],
            ]
        ]);
    }

    /**
     * 账号注册接口
     * @RequestMapping(path="register", methods="post")
     * @return mixed
     */
    public function register()
    {
        $params = $this->request->all();
        $this->validate($params, [
            'nickname' => "required|max:20",
            'mobile' => "required|regex:/^1[345789][0-9]{9}$/",
            'password' => 'required|max:16',
            'sms_code' => 'required|digits:6',
            'platform' => 'required|in:h5,ios,windows,mac,web',
        ]);

        $isTrue = $this->userService->register([
            'mobile' => $params['mobile'],
            'password' => $params['password'],
            'nickname' => strip_tags($params['nickname']),
        ]);

        if (!$isTrue) return $this->response->fail('账号注册失败...');
        return $this->success('账号注册成功');
    }

    /**
     * 退出登录接口
     * @RequestMapping(path="logout", methods="get")
     * @Middleware(AuthMiddleware::class)
     * @return mixed
     */
    public function logout()
    {
        $this->logoutToken();
        return $this->success('退出成功');
    }

    /**
     * 获取个人信息
     * @RequestMapping(path="user", methods="get")
     * @Middleware(AuthMiddleware::class)
     */
    public function me()
    {
        return $this->success($this->userService->findById($this->uid(), 'id, username, mobile'));
    }
}
