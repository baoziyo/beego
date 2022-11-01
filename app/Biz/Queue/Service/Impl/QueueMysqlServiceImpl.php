<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Queue\Service\Impl;

use App\Biz\Queue\Config\BaseQueueHandle;
use App\Biz\Queue\Dao\QueueMysqlDaoImpl;
use App\Biz\Queue\Exception\QueueException;
use App\Biz\Queue\Service\QueueFailService;
use App\Biz\Queue\Service\QueueMysqlService;
use App\Biz\Queue\Service\QueueService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;
use App\Utils\ErrorTools;
use Carbon\Carbon;
use Hyperf\Database\Model\Collection;
use Throwable;

class QueueMysqlServiceImpl extends BaseServiceImpl implements QueueMysqlService
{
    public function get(int $id): QueueMysqlDaoImpl|null
    {
        return QueueMysqlDaoImpl::findFromCache($id);
    }

    public function getById(int $id): QueueMysqlDaoImpl
    {
        $mysql = $this->get($id);
        if ($mysql === null) {
            throw new QueueException(QueueException::NOT_FUND_QUEUE_MYSQL);
        }

        return $mysql;
    }

    /**
     * @param array $ids
     * @return Collection<int,QueueMysqlDaoImpl>
     */
    public function find(array $ids): Collection
    {
        return QueueMysqlDaoImpl::findManyFromCache($ids);
    }

    public function create(QueueMysqlDaoImpl $dao): QueueMysqlDaoImpl
    {
        $dao->save();

        return $dao;
    }

    public function producer(int $id, int $delay = 0): bool
    {
        $dao = new QueueMysqlDaoImpl();
        $dao->setId($id);
        $dao->setSendTime(Carbon::now()->addSeconds($delay));

        $this->create($dao);

        return true;
    }

    public function consumer(): bool
    {
        $queueMysqlLists = QueueMysqlDaoImpl::query()->where('sendTime', '<=', Carbon::now())->pluck('id');
        if ($queueMysqlLists->isEmpty()) {
            return true;
        }

        $queueMysqlLists->map(function ($queueId) {
            $queue = $this->getQueueService()->get($queueId);

            if ($queue !== null) {
                $template = $queue->template;
                /** @var BaseQueueHandle $template */
                $template = new $template($this->biz);

                try {
                    $queue->sendUserIds = $this->getQueueService()->getNotSendUserIds($queue->id);
                    $response = $template->handle($queue->params);

                    if (!empty($response['failUserIds'])) {
                        $this->getQueueService()->failed($queue->id, $response['failUserIds'], $response['failDetails'] ?? []);
                        $this->failed($queue->id);
                    } else {
                        $this->getQueueService()->finished($queue->id);
                        $this->finished($queue->id);
                    }
                } catch (Throwable $e) {
                    $this->getQueueService()->failed($queue->id, ['all'], ErrorTools::generateErrorInfo($e));
                    $this->failed($queue->id);
                    throw $e;
                }
            }
        });

        return true;
    }

    public function delete(int $id): bool
    {
        $mysql = $this->get($id);
        if ($mysql === null) {
            return false;
        }

        $mysql->delete();

        return true;
    }

    protected function failed(int $id): void
    {
        $queueMysql = $this->get($id);
        if ($queueMysql === null) {
            return;
        }

        $queueFail = $this->getQueueFailService()->get($id);
        if ($queueFail === null) {
            return;
        }

        if ($queueFail->sendCount < 3) {
            return;
        }

        $this->delete($queueMysql->id);
    }

    protected function finished(int $id): void
    {
        $this->delete($id);
    }

    protected function getQueueService(): QueueService
    {
        return $this->biz->getService('Queue:Queue');
    }

    protected function getQueueFailService(): QueueFailService
    {
        return $this->biz->getService('Queue:QueueFail');
    }
}
