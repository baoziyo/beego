<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Queue\Service\Impl;

use App\Biz\Queue\Config\BaseMailTemplate;
use App\Biz\Queue\Dao\QueueMysqlDaoImpl;
use App\Biz\Queue\Service\QueueFailService;
use App\Biz\Queue\Service\QueueMysqlService;
use App\Biz\Queue\Service\QueueService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;
use App\Utils\ErrorTools;
use Hyperf\Database\Model\Collection;
use Throwable;

class QueueMysqlServiceImpl extends BaseServiceImpl implements QueueMysqlService
{
    public function get(mixed $id): QueueMysqlDaoImpl|null
    {
        return QueueMysqlDaoImpl::findFromCache($id);
    }

    /**
     * @param array $ids
     * @return Collection<int,QueueMysqlDaoImpl>
     */
    public function find(array $ids): Collection
    {
        return QueueMysqlDaoImpl::findManyFromCache($ids);
    }

    public function create(array $fields): QueueMysqlDaoImpl
    {
        $dao = new QueueMysqlDaoImpl();
        $dao->fill($fields);
        $dao->save();

        return $dao;
    }

    public function producer(mixed $id, int $delay = 0): bool
    {
        $this->create([
            'id' => $id,
            'sendTime' => time() + $delay,
        ]);

        return true;
    }

    public function consumer(): bool
    {
        $queueMysqlLists = QueueMysqlDaoImpl::query()->where('sendTime', '<=', time())->pluck('id');
        if ($queueMysqlLists->isEmpty()) {
            return true;
        }

        $queueMysqlLists->map(function (QueueMysqlDaoImpl $queueMysqlList) {
            $queue = $this->getQueueService()->get($queueMysqlList->id);

            if ($queue !== null) {
                /** @var BaseMailTemplate $template */
                $template = new $queue->template();

                try {
                    $queue->sendUserIds = $this->getQueueService()->getNotSendUserIds($queue->id);
                    $response = $template->handle($queue->params);

                    if (!empty($response['failUserIds'])) {
                        $this->getQueueService()->failed($response['id'], $response['failUserIds'], $response['failDetails']);
                        $this->failed($response['id']);
                    } else {
                        $this->getQueueService()->finished($response['id']);
                    }
                } catch (Throwable $e) {
                    $this->getQueueService()->failed($queue->id, ['all'], ErrorTools::generateErrorInfo($e));
                    $this->failed($queue->id);
                    throw new $e();
                }
            }
        });

        return true;
    }

    protected function failed(mixed $id): void
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

        $queueMysql->delete();
    }

    protected function finished(mixed $id): void
    {
        $queueMysql = $this->get($id);
        if ($queueMysql === null) {
            return;
        }

        $queueMysql->delete();
    }

    private function getQueueService(): QueueService
    {
        return $this->biz->getService('Queue:Queue');
    }

    private function getQueueFailService(): QueueFailService
    {
        return $this->biz->getService('Queue:QueueFail');
    }
}
