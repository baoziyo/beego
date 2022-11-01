<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Token\Service\Impl;

use App\Biz\Token\Dao\TokenDaoImpl;
use App\Biz\Token\Service\TokenService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;
use Hyperf\Database\Model\Collection;

class TokenServiceImpl extends BaseServiceImpl implements TokenService
{
    public function get(mixed $id): TokenDaoImpl|null
    {
        return TokenDaoImpl::findFromCache($id);
    }

    /**
     * @param array $ids
     * @return Collection<int,TokenDaoImpl>
     */
    public function find(array $ids): Collection
    {
        return TokenDaoImpl::findManyFromCache($ids);
    }

    public function create(string $key, string $value, int $expires): TokenDaoImpl
    {
        $dao = new TokenDaoImpl();
        $dao->fill([
            'key' => $key,
            'value' => $value,
            'expires' => $expires,
            'expiresTime' => time() + $expires,
            'createdTime' => time(),
        ]);
        $dao->save();

        return $dao;
    }

    public function getByKey(string $key): TokenDaoImpl|null
    {
        $id = TokenDaoImpl::query()->where('key', $key)->value('id');
        if ($id === null) {
            return null;
        }

        return $this->get($id);
    }

    public function getToken(string $key): string
    {
        $token = $this->getByKey($key);
        if ($token === null) {
            return '';
        }

        if ($token->expiresTime->getTimestamp() < time()) {
            return '';
        }

        return $token->value;
    }

    public function updateToken(string $key, string $value, int $expires): bool
    {
        $token = $this->getByKey($key);
        if ($token === null) {
            $this->create($key, $value, $expires);
            return true;
        }

        $token->fill([
            'value' => $value,
            'expires' => $expires,
            'expiresTime' => time() + $expires,
        ]);
        $token->save();

        return true;
    }
}
