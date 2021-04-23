<?php

namespace App\Common\Middleware;

use App\Common\Constants\ResponseCode;
use App\Exception\CommonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Phper666\JWTAuth\JWT;
use Phper666\JWTAuth\Util\JWTUtil;

/**
 * 授权验证中间件
 * Class AuthMiddleware
 * @package App\Common\Middleware
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @var JWT
     */
    protected $jwt;

    public function __construct(JWT $jwt)
    {
        $this->jwt = $jwt;
    }

    /**
     * jwt验证过程
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        # 获取请求token
        $token = JWTUtil::handleToken($request->getHeaderLine('Authorization'));
        # token获取
        if (false === $token) throw new CommonException(ResponseCode::LOGIN_ERROR_MSG, ResponseCode::LOGIN_ERROR);
        try {
            # 验证token
            $this->jwt->checkToken($token);
        } catch (\Exception $e) {
            throw new CommonException(ResponseCode::LOGIN_EXPIRED_MSG, ResponseCode::LOGIN_EXPIRED);
        }
        return $handler->handle($request);
    }
}
