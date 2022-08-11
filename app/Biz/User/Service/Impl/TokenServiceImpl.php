<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\User\Service\Impl;

use App\Biz\User\Config\TokenStrategy;
use App\Biz\User\Exception\TokenException;
use App\Biz\User\Service\TokenService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;
use Hyperf\HttpServer\Contract\RequestInterface;

class TokenServiceImpl extends BaseServiceImpl implements TokenService
{
    public function generateToken(array $params): array
    {
        $type = $params['type'];
        $token = $this->getTokenStrategy($type)->generateToken($params);

        return array_merge([
            'type' => $type,
        ], $token);
    }

    public function refreshToken(array $params): array
    {
        $type = $params['type'];
        $token = $this->getTokenStrategy($type)->refreshToken($params['refreshToken']);

        return array_merge([
            'type' => $type,
        ], $token);
    }

    public function validate(string $type, RequestInterface $request): array
    {
        return $this->getTokenStrategy($type)->validate($request);
    }

    private function getTokenStrategy(string $type): TokenStrategy
    {
        if (!isset(self::TOKEN_STRATEGY_TYPE[$type])) {
            throw new TokenException(TokenException::TOKEN_TYPE_ERROR);
        }

        return make(self::TOKEN_STRATEGY_TYPE[$type]);
    }
}
