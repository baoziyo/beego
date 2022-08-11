<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Token\Service;

use App\Biz\Token\Dao\TokenDaoImpl;
use App\Core\Biz\Service\BaseService;
use Hyperf\Database\Model\Collection;

interface TokenService extends BaseService
{
    public const WECHAT_ACCESS_TOKEN = 'wechat_access_token';

    public function get(mixed $id): TokenDaoImpl|null;

    public function find(array $ids): Collection;

    public function create(string $key, string $value, int $expires): TokenDaoImpl;

    public function getByKey(string $key): TokenDaoImpl|null;

    public function getToken(string $key): string;

    public function updateToken(string $key, string $value, int $expires): bool;
}
