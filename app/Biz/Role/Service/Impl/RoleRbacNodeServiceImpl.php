<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Role\Service\Impl;

use App\Biz\Role\Dao\RoleRbacNodeDaoImpl;
use App\Biz\Role\Service\RoleRbacNodeService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;
use Hyperf\Database\Model\Collection;

class RoleRbacNodeServiceImpl extends BaseServiceImpl implements RoleRbacNodeService
{
    public function get(mixed $id): RoleRbacNodeDaoImpl|null
    {
        return RoleRbacNodeDaoImpl::findFromCache($id);
    }

    /**
     * @param array $ids
     * @return Collection<int,RoleRbacNodeDaoImpl>
     */
    public function find(array $ids): Collection
    {
        return RoleRbacNodeDaoImpl::findManyFromCache($ids);
    }
}
