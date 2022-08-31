<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Queue\Service;

use App\Biz\Queue\Dao\QueueMysqlDaoImpl;
use App\Core\Biz\Service\BaseService;
use Hyperf\Database\Model\Collection;

interface QueueMysqlService extends BaseService
{
    public function get(mixed $id): QueueMysqlDaoImpl|null;

    public function find(array $ids): Collection;

    public function create(array $fields): QueueMysqlDaoImpl;

    public function producer(mixed $id, int $delay = 0): bool;

    public function consumer(): bool;
}
