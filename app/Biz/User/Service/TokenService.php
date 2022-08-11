<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare (strict_types=1);
namespace App\Biz\User\Service;

use App\Biz\User\Type\Token\Jwt;
use App\Core\Biz\Service\BaseService;
use Hyperf\HttpServer\Contract\RequestInterface;
interface TokenService extends BaseService
{
    public const TOKEN_STRATEGY_TYPE = ['jwt' => Jwt::class];
    public function generateToken(array $params) : array;
    public function refreshToken(array $params) : array;
    public function validate(string $type, RequestInterface $request) : array;
}