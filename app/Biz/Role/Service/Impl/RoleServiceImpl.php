<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Role\Service\Impl;

use App\Biz\Role\Dao\RoleDaoImpl;
use App\Biz\Role\Exception\RoleException;
use App\Biz\Role\Service\RoleRbacNodeService;
use App\Biz\Role\Service\RoleService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;
use Hyperf\Database\Model\Collection;

class RoleServiceImpl extends BaseServiceImpl implements RoleService
{
    public function get(mixed $id): RoleDaoImpl|null
    {
        return RoleDaoImpl::findFromCache($id);
    }

    /**
     * @param array $ids
     * @return Collection<int,RoleDaoImpl>
     */
    public function find(array $ids): Collection
    {
        return RoleDaoImpl::findManyFromCache($ids);
    }

    public function create(array $fields): RoleDaoImpl|null
    {
        $dao = new RoleDaoImpl();
        $dao->fill($fields);
        $dao->save();

        return $dao;
    }

    /**
     * @return Collection<int,RoleDaoImpl>
     */
    public function findAll(): Collection
    {
        $ids = RoleDaoImpl::query()->pluck('id');

        return $this->find($ids->toArray());
    }

    public function getByCode(string $code): RoleDaoImpl|null
    {
        $id = RoleDaoImpl::query()->where('code', $code)->value('id');
        if ($id === null) {
            return null;
        }

        return $this->get($id);
    }

    public function isPermission(mixed $roleId, string $uri): void
    {
        $role = $this->get($roleId);
        if ($role === null) {
            throw new RoleException(RoleException::NOT_FOUND);
        }

        $rbacNodes = $this->getRbacNodeService()->find($role->data);

        $rbacNodeLinks = array_column($rbacNodes->toArray(), null, 'link');

        if (!in_array($uri, $rbacNodeLinks, true)) {
            throw new RoleException(RoleException::NOT_FOUND);
        }
    }

    private function getRbacNodeService(): RoleRbacNodeService
    {
        return $this->biz->getService('Role:RoleRbacNode');
    }
}
