<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Queue\Service;

use App\Biz\Queue\Dao\QueueFailDaoImpl;
use App\Core\Biz\Service\BaseService;
use Hyperf\Database\Model\Collection;

interface QueueFailService extends BaseService
{
    public function get(int $id): QueueFailDaoImpl|null;

    public function getById(int $id): QueueFailDaoImpl;

    public function find(array $ids): Collection;

    public function create(QueueFailDaoImpl $dao): QueueFailDaoImpl;

    public function getByTargetId(int $id): QueueFailDaoImpl|null;
}
