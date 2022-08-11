<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\User\Service\Impl;

use App\Biz\User\Dao\UserBindDaoImpl;
use App\Biz\User\Service\UserBindService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;
use Hyperf\Database\Model\Collection;

class UserBindServiceImpl extends BaseServiceImpl implements UserBindService
{
    public function get(mixed $id): UserBindDaoImpl|null
    {
        return UserBindDaoImpl::findFromCache($id);
    }

    /**
     * @param array $ids
     * @return Collection<int,UserBindDaoImpl>
     */
    public function find(array $ids): Collection
    {
        return UserBindDaoImpl::findManyFromCache($ids);
    }

    public function create(array $fields): UserBindDaoImpl
    {
        $dao = new UserBindDaoImpl();
        $dao->fill($fields);
        $dao->save();

        return $dao;
    }

    public function createOrUpdate(string $fromId, string $type, array $fields): bool
    {
        $userBind = $this->getByUserIdAndType($fromId, $type);
        if ($userBind === null) {
            $this->create($fields);
            return true;
        }

        $userBind->fill($fields);
        $userBind->save();

        return true;
    }

    public function getByFromId(string $fromId): UserBindDaoImpl|null
    {
        $id = UserBindDaoImpl::query()->where('fromId', $fromId)->value('id');
        if ($id === null) {
            return null;
        }

        return $this->get($id);
    }

    public function getFromIdByUserIdAndType(mixed $userId, string $type): string|null
    {
        return UserBindDaoImpl::query()
            ->where('userId', $userId)
            ->where('type', $type)
            ->value('fromId');
    }

    public function getByUserIdAndType(mixed $userId, string $type): UserBindDaoImpl|null
    {
        $id = $this->getFromIdByUserIdAndType($userId, $type);

        if ($id === null) {
            return null;
        }

        return $this->get($id);
    }

    public function getIdByFromIdAndType(string $fromId, string $type): UserBindDaoImpl|null
    {
        $id = UserBindDaoImpl::query()
            ->where('fromId', $fromId)
            ->where('type', $type)
            ->value('id');
        if ($id === null) {
            return null;
        }

        return $this->get($id);
    }

    public function getByFromIdAndType(string $fromId, string $type): UserBindDaoImpl|null
    {
        $id = $this->getIdByFromIdAndType($fromId, $type);
        if ($id === null) {
            return null;
        }

        return $this->get($id);
    }
}
