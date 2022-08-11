<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Queue\Service\Impl;

use App\Biz\Queue\Config\QueueStrategy;
use App\Biz\Queue\Dao\QueueDaoImpl;
use App\Biz\Queue\Exception\QueueException;
use App\Biz\Queue\Service\QueueFailService;
use App\Biz\Queue\Service\QueueService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;
use App\Utils\ErrorTools;
use Hyperf\Database\Model\Collection;
use Hyperf\DbConnection\Db;
use Throwable;

class QueueServiceImpl extends BaseServiceImpl implements QueueService
{
    public function get(mixed $id): QueueDaoImpl|null
    {
        return QueueDaoImpl::findFromCache($id);
    }

    /**
     * @param array $ids
     * @return Collection<int,QueueDaoImpl>
     */
    public function find(array $ids): Collection
    {
        return QueueDaoImpl::findManyFromCache($ids);
    }

    public function create(array $fields): QueueDaoImpl
    {
        $dao = new QueueDaoImpl();
        $dao->fill($fields);
        $dao->save();

        return $dao;
    }

    public function send(string $queueType, array $sendTypes, string $templateType, array $userIds = [], array $params = [], int $delay = 0): bool|array
    {
        $sendFails = [];
        if (!$this->getQueueStrategy($queueType)->beforeSendValidateQueue()) {
            throw new QueueException(QueueException::QUEUE_TYPE_DISABLED, null, null, [$queueType]);
        }
        foreach ($sendTypes as $sendType) {
            $queue = $this->create([
                'queue' => $queueType,
                'type' => $sendType,
                'template' => $templateType,
                'params' => $params,
                'sendUserIds' => $userIds,
                'delay' => $delay,
            ]);

            $params['id'] = $queue->id;
            if (!$this->getQueueStrategy($queueType)->producer($sendType, $templateType, $params, $delay)) {
                $sendFails[] = $templateType;
            }
        }
        if (!empty($sendFails)) {
            return $sendFails;
        }
        return true;
    }

    public function failed(mixed $id, array $failUserIds, array $failDetails): bool
    {
        $queue = $this->get($id);
        if ($queue === null) {
            throw new QueueException(QueueException::NOT_FUND_QUEUE_JOB, null, null, [$id]);
        }

        if ($failUserIds === ['all']) {
            $failUserIds = $queue->sendUserIds;
        }

        $queue->status = self::FAILED;

        Db::beginTransaction();
        try {
            $queueFail = $this->getQueueFailService()->getByTargetId($id);
            if ($queueFail === null) {
                $this->getQueueFailService()->create([
                    'targetId' => $id,
                    'failUserIds' => $failUserIds,
                    'failDetails' => [$failDetails],
                ]);
            } else {
                $queueFail->increment('sendCount');
                $queueFail->fill([
                    'failDetails' => array_merge($queueFail->failDetails, $failDetails),
                    'failUserIds' => $failUserIds,
                ]);
                $queueFail->save();
            }

            $queue->save();

            Db::commit();

            return true;
        } catch (Throwable $e) {
            Db::rollBack();
            $this->getLogService()->error('队列保存执行失败信息错误', ErrorTools::generateErrorInfo($e));

            return false;
        }
    }

    public function finished(mixed $id): bool
    {
        $queue = $this->get($id);
        if ($queue === null) {
            throw new QueueException(QueueException::NOT_FUND_QUEUE_JOB, null, null, [$id]);
        }
        $queue->status = self::FINISHED;
        $queue->save();

        return true;
    }

    public function getNotSendUserIds(mixed $id): array
    {
        $queue = $this->get($id);
        $queueFail = $this->getQueueFailService()->get($id);
        if ($queue !== null && $queueFail !== null) {
            return array_diff($queue->sendUserIds, $queueFail->failUserIds);
        }

        return [];
    }

    private function getQueueStrategy(string $type): QueueStrategy
    {
        if (!isset(self::QUEUE_STRATEGY_TYPE[$type])) {
            throw new QueueException(QueueException::NOT_FUND_QUEUE);
        }

        return make(self::QUEUE_STRATEGY_TYPE[$type], [$this->biz]);
    }

    private function getQueueFailService(): QueueFailService
    {
        return $this->biz->getService('Queue:QueueFail');
    }
}
