<?php

namespace App\Common\Middleware;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Phper666\JWTAuth\JWT;
use Phper666\JWTAuth\Util\JWTUtil;
use Hyperf\Utils\Context;

/**
 * 用户信息中间件
 * Class UserMiddleware
 * @package App\Common\Middleware
 */
class UserMiddleware implements MiddlewareInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var HttpResponse
     */
    protected $response;

    /**
     * @var JWT
     */
    protected $jwt;

    public function __construct(HttpResponse $response, RequestInterface $request, JWT $jwt)
    {
        $this->response = $response;
        $this->request = $request;
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
        // 获取请求token
        $token = $request->getHeaderLine('Authorization');

        if (empty($token)) {
            $token = $this->request->input('token', '');
        }
        if (strlen($token) > 0) {
            try {
                $token = JWTUtil::handleToken($token);

                if ($token !== false && $this->jwt->checkToken($token)) {
                    $request = Context::get(ServerRequestInterface::class);
                    $user = $this->jwt->getParserData($token);
                    $request = $request->withAttribute('user', $user);
                    Context::set(ServerRequestInterface::class, $request);
                }
            } catch (\Exception $e) {
                return $this->response->json([
                    'code' => 401,
                    'message' => '请先登陆~',
                ]);
            }
        }
        return $handler->handle($request);
    }
}
