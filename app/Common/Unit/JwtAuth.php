<?php

namespace App\Common\Unit;

use Firebase\JWT\JWT;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

/**
 * Jwt
 * Class JwtAuth
 * @package App\Common\Unit
 */
class JwtAuth
{
    /**
     * token
     * @var
     */
    protected $token;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * 获取token
     * @param int $id
     * @param string $type
     * @param array $params
     * @return array
     */
    public function getToken(int $id, string $type, array $params = []): array
    {
        //$host = $this->request->host();
        $host = '192.168.137.199';
        $time = time();

        $params += [
            'iss' => $host,
            'aud' => $host,
            'iat' => $time,
            'nbf' => $time,
            'exp' => strtotime('+ 3hour'),
        ];
        $params['jti'] = compact('id', 'type');
        $token = JWT::encode($params, env('APP_KEY', 'hadream'));
        return compact('token', 'params');
    }

    /**
     * 解析token
     * @param string $jwt
     * @return array
     */
    public function parseToken(string $jwt): array
    {
        $this->token = $jwt;
        list($headb64, $bodyb64, $cryptob64) = explode('.', $this->token);
        $payload = JWT::jsonDecode(JWT::urlsafeB64Decode($bodyb64));
        return [$payload->jti->id, $payload->jti->type];
    }

    /**
     * 验证token
     */
    public function verifyToken()
    {
        JWT::$leeway = 60;
        JWT::decode($this->token, env('APP_KEY', 'hadream'), array('HS256'));
        $this->token = null;
    }

    ///**
    // * 获取token并放入令牌桶
    // * @param int $id
    // * @param string $type
    // * @param array $params
    // * @return array
    // */
    //public function createToken(int $id, string $type, array $params = [])
    //{
    //    $tokenInfo = $this->getToken($id, $type, $params);
    //    $exp = $tokenInfo['params']['exp'] - $tokenInfo['params']['iat'] + 60;
    //    $res = CacheService::setTokenBucket($tokenInfo['token'], ['uid' => $id, 'type' => $type, 'token' => $tokenInfo['token'], 'exp' => $exp], (int)$exp);
    //    if (!$res) {
    //        throw new AdminException(ApiErrorCode::ERR_SAVE_TOKEN);
    //    }
    //    return $tokenInfo;
    //}
}