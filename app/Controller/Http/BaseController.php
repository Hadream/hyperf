<?php

declare(strict_types=1);

namespace App\Controller\Http;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;
use App\Common\Constants\ResponseCode;

/**
 * Http基类
 * Class BaseController
 * @package App\Controller\Http
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
