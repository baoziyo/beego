<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\User\Service;

use App\Biz\User\Dao\UserBindDaoImpl;
use App\Core\Biz\Service\BaseService;
use Hyperf\Database\Model\Collection;

interface UserBindService extends BaseService
{
    public const WECHAT_TYPE = 'weChatApp';

    public function get(mixed $id): UserBindDaoImpl|null;

    public function find(array $ids): Collection;

    public function create(array $fields): UserBindDaoImpl;

    public function createOrUpdate(array $where, array $fields): bool;

    public function getByFromId(string $fromId): UserBindDaoImpl|null;

    public function getFromIdByUserIdAndType(mixed $userId, string $type): string|null;

    public function getByUserIdAndType(mixed $userId, string $type): UserBindDaoImpl|null;

    public function getIdByFromIdAndType(string $fromId, string $type): UserBindDaoImpl|null;

    public function getByFromIdAndType(string $fromId, string $type): UserBindDaoImpl|null;
}
