<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Queue\Service\Impl;

use App\Biz\Queue\Dao\QueueFailDaoImpl;
use App\Biz\Queue\Service\QueueFailService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;
use Hyperf\Database\Model\Collection;

class QueueFailServiceImpl extends BaseServiceImpl implements QueueFailService
{
    public function get(mixed $id): QueueFailDaoImpl|null
    {
        return QueueFailDaoImpl::findFromCache($id);
    }

    /**
     * @param array $ids
     * @return Collection<int,QueueFailDaoImpl>
     */
    public function find(array $ids): Collection
    {
        return QueueFailDaoImpl::findManyFromCache($ids);
    }

    public function create(array $fields): QueueFailDaoImpl
    {
        $dao = new QueueFailDaoImpl();
        $dao->fill($fields);
        $dao->save();

        return $dao;
    }

    public function getByTargetId(mixed $id): QueueFailDaoImpl|null
    {
        $queueFailId = QueueFailDaoImpl::query()->where('targetId', $id)->value('id');

        if ($queueFailId === null) {
            return null;
        }

        return $this->get($queueFailId);
    }
}
