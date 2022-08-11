<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Role\Service;

use App\Biz\Role\Dao\RoleDaoImpl;
use App\Core\Biz\Service\BaseService;
use Hyperf\Database\Model\Collection;

interface RoleService extends BaseService
{
    public function get(mixed $id): RoleDaoImpl|null;

    public function find(array $ids): Collection;

    public function create(array $fields): RoleDaoImpl|null;

    public function findAll(): Collection;

    public function getByCode(string $code): RoleDaoImpl|null;

    public function isPermission(mixed $roleId, string $uri): void;
}
