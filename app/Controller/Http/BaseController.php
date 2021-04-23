<?php

declare(strict_types=1);

namespace App\Controller\Http;

use App\Common\Constants\ResponseCode;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;
use Phper666\JWTAuth\JWT;
use Phper666\JWTAuth\Util\JWTUtil;

/**
 * Api基类
 * Class BaseController
 * @package App\Controller\Api
 */
abstract class BaseController
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;

    /**
     * 获取用户信息
     * @return array
     */
    protected function user()
    {
        // 获取请求token
        $token = $this->request->getHeaderLine('Authorization');
        if (!$token) $token = $this->request->input('token', '');
        try {
            $jwt = make(JWT::class);
            $token = JWTUtil::handleToken($token);
            if (!$token || !$jwt->checkToken($token)) return [];
            $info = $jwt->getParserData($token);
            return ['user_id' => $info['user_id'], 'platform' => $info['platform']];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * 获取用户信息
     * @return array
     */
    protected function uid()
    {
        // 获取请求token
        $token = $this->request->getHeaderLine('Authorization');
        if (!$token) $token = $this->request->input('token', '');
        try {
            $jwt = make(JWT::class);
            $token = JWTUtil::handleToken($token);
            if (!$token || !$jwt->checkToken($token)) return null;
            $info = $jwt->getParserData($token);
            return $info['user_id'];
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * 生成token
     * @param $data
     * @return mixed
     */
    protected function getToken($data)
    {
        try {
            $token = make(JWT::class)->getToken($data);
        } catch (\Exception $e) {
            return $this->error('登录异常，请稍后再试^_^');
        }
        return $token;
    }

    /**
     * 获取token有效期
     * @return mixed
     */
    protected function getTTLToken()
    {
        return make(JWT::class)->getTTL();
    }

    /**
     * 注销当前token
     */
    protected function logoutToken()
    {
        make(JWT::class)->logout();
    }

    /**
     * 成功接口
     * @param string $msg
     * @param array|null $data
     * @param $code
     * @param int $http_code
     * @return mixed
     */
    protected function success($msg = 'ok', array $data = null, $code = ResponseCode::SUCCESS, $http_code = 200)
    {
        if (is_array($msg)) {
            $data = $msg;
            $msg = 'ok';
        }
        $res = compact('code', 'msg');
        if (!is_null($data)) $res['data'] = $data;
        return $this->response->withStatus($http_code)->json($res);
    }

    /**
     * 错误接口
     * @param string $msg
     * @param array|null $data
     * @param $code
     * @param int $http_code
     * @return mixed
     */
    protected function error($msg = 'fail', array $data = null, $code = ResponseCode::FAIL, $http_code = 200)
    {
        if (is_array($msg)) {
            $data = $msg;
            $msg = 'fail';
        }
        $res = compact('code', 'msg');
        if (!is_null($data)) $res['data'] = $data;
        return $this->response->withStatus($http_code)->json($res);
    }
}
