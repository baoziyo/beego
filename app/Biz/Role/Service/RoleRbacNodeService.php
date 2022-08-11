<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Role\Service;

use App\Biz\Role\Dao\RoleRbacNodeDaoImpl;
use App\Core\Biz\Service\BaseService;
use Hyperf\Database\Model\Collection;

interface RoleRbacNodeService extends BaseService
{
    public function get(mixed $id): RoleRbacNodeDaoImpl|null;

    public function find(array $ids): Collection;
}
