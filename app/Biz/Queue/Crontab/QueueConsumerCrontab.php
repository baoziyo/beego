<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Queue\Crontab;

use App\Biz\Queue\Service\QueueMysqlService;
use App\Core\Crontab\BaseCrontab;
use Hyperf\Crontab\Annotation\Crontab;

#[Crontab(rule: '* * * * *', name: 'queueConsumer', callback: 'execute', memo: '消耗mysql队列任务', enable: 'isEnable')]
class QueueConsumerCrontab extends BaseCrontab
{
    public function isEnable(): bool
    {
        return env('QUEUE_MYSQL', false);
    }

    public function execute(): bool
    {
        $this->getQueueMysqlService()->consumer();

        return true;
    }

    private function getQueueMysqlService(): QueueMysqlService
    {
        return $this->biz->getService('Queue:QueueMysql');
    }
}
