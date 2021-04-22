<?php

namespace App\Controller\Api;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Common\Unit\JwtAuth;

use App\Controller\Api\BaseController;
use App\Request\Api\AuthRequest;

use App\Middleware\JWTAuthMiddleware;
use App\Common\Constants\ResponseCode;
use App\Model\User;
use App\Service\UserService;

/**
 * 授权相关控制器
 * @Controller(path="/api/v1/auth")
 */
class AuthController extends BaseController
{
    /**
     * @Inject
     * @var UserService
     */
    private $userService;

    /**
     * @Inject
     * @var JwtAuth
     */
    protected $jwt;

    /**
     * 授权登录接口
     * @RequestMapping(path="login", methods="post")
     */
    public function login(AuthRequest $request)
    {
        $params = $request->validated();

        $userInfo = $this->userService->login($params['mobile'], $params['password'], $params['platform']);
        if (!$userInfo) return $this->error('账号不存在或密码填写错误^_^');
        $token = $this->jwt->getToken($userInfo['id'], $params['platform']);
        //try {
        //    $jwtInfo = [
        //        'user_id' => $userInfo['id'],
        //        'platform' => $params['platform'],
        //    ];
        //    $token = $this->jwt->getToken();
        //    //$token = $this->jwt->getToken();
        //} catch (\Exception $exception) {
        //    return $this->error('登录异常，请稍后再试^_^');
        //}
        var_dump($token);

        return $this->success([
            'authorize' => [
                'token' => $token,
                //'expires_in' => $this->jwt->getTTL()
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
     *
     * @RequestMapping(path="register", methods="post")
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

        if (!$this->smsCodeService->check('user_register', $params['mobile'], $params['sms_code'])) {
            return $this->response->fail('验证码填写错误...');
        }

        $isTrue = $this->userService->register([
            'mobile' => $params['mobile'],
            'password' => $params['password'],
            'nickname' => strip_tags($params['nickname']),
        ]);

        if (!$isTrue) {
            return $this->response->fail('账号注册失败...');
        }

        // 删除验证码缓存
        $this->smsCodeService->delCode('user_register', $params['mobile']);

        return $this->response->success([], '账号注册成功...');
    }

    /**
     * 退出登录接口
     *
     * @RequestMapping(path="logout", methods="post")
     * @Middleware(JWTAuthMiddleware::class)
     */
    public function logout()
    {
        $this->jwt->logout();

        return $this->response->success([], 'Successfully logged out');
    }
}
