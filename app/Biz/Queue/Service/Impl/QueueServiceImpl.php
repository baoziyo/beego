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
use App\Biz\Queue\Dao\QueueFailDaoImpl;
use App\Biz\Queue\Exception\QueueException;
use App\Biz\Queue\Params\QueueSendParams;
use App\Biz\Queue\Service\QueueFailService;
use App\Biz\Queue\Service\QueueMysqlService;
use App\Biz\Queue\Service\QueueService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;
use App\Utils\ErrorTools;
use Hyperf\Database\Model\Collection;
use Hyperf\DbConnection\Db;
use Throwable;

class QueueServiceImpl extends BaseServiceImpl implements QueueService
{
    public function get(int $id): QueueDaoImpl|null
    {
        return QueueDaoImpl::findFromCache($id);
    }

    public function getById(int $id): QueueDaoImpl
    {
        $queue = $this->get($id);
        if ($queue === null) {
            throw new QueueException(QueueException::NOT_FUND_QUEUE);
        }

        return $queue;
    }

    /**
     * @param array $ids
     * @return Collection<int,QueueDaoImpl>
     */
    public function find(array $ids): Collection
    {
        return QueueDaoImpl::findManyFromCache($ids);
    }

    public function create(QueueDaoImpl $dao): QueueDaoImpl
    {
        $dao->save();

        return $dao;
    }

    public function createQueue(QueueSendParams $params): bool|array
    {
        $sendFails = [];
        if (!$this->getQueueStrategy($params->queueType)->beforeSendValidateQueue()) {
            throw new QueueException(QueueException::QUEUE_TYPE_DISABLED, params: [$params->queueType]);
        }
        foreach ($params->sendTypes as $sendType) {
            $queueDao = new QueueDaoImpl();
            $queueDao->setName($params->name ?? $params->template);
            $queueDao->setQueue($params->queueType);
            $queueDao->setType($sendType);
            $queueDao->setTemplate($params->template);
            $queueDao->setParams($params->params);
            $queueDao->setSendUserIds($params->userIds);
            $queueDao->setDelay($params->delay);
            $queue = $this->create($queueDao);

            $params['id'] = $queue->id;
            if (!$this->getQueueStrategy($params->queueType)->producer($sendType, $params->template, $params, $params->delay)) {
                $sendFails[] = $params->template;
            }
        }
        if (!empty($sendFails)) {
            return $sendFails;
        }
        return true;
    }

    public function failed(int $id, array $failUserIds, array $failDetails): bool
    {
        $queue = $this->get($id);
        if ($queue === null) {
            throw new QueueException(QueueException::NOT_FUND_QUEUE_JOB, params: [$id]);
        }

        if ($failUserIds === ['all']) {
            $failUserIds = $queue->sendUserIds;
        }

        $queue->status = self::FAILED;

        Db::beginTransaction();
        try {
            $queueFail = $this->getQueueFailService()->getByTargetId($id);
            if ($queueFail === null) {
                $queueFailDao = new QueueFailDaoImpl();
                $queueFailDao->setTargetId($id);
                $queueFailDao->setFailUserIds($failUserIds);
                $queueFailDao->setFailDetails([$failDetails]);
                $this->getQueueFailService()->create($queueFailDao);
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

    public function finished(int $id): bool
    {
        $queue = $this->get($id);
        if ($queue === null) {
            throw new QueueException(QueueException::NOT_FUND_QUEUE_JOB, params: [$id]);
        }
        $queue->status = self::FINISHED;
        $queue->save();

        return true;
    }

    public function getNotSendUserIds(int $id): array
    {
        $queue = $this->get($id);
        $queueFail = $this->getQueueFailService()->get($id);
        if ($queue !== null && $queueFail !== null) {
            return array_diff($queue->sendUserIds, $queueFail->failUserIds);
        }

        return [];
    }

    public function deleteByName(string $name): void
    {
        $queueIds = QueueDaoImpl::query(true)->where('name', $name)->pluck('id')->toArray();

        $queues = $this->find($queueIds);
        $queues->map(function (QueueDaoImpl $queue) {
            $this->getQueueMysqlService()->delete($queue->id);
            $queue->delete();
        });
    }

    protected function getQueueMysqlService(): QueueMysqlService
    {
        return $this->biz->getService('Queue:QueueMysql');
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
