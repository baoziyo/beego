<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Queue\Type\Queue;

use App\Biz\Queue\Service\QueueMysqlService;
use App\Biz\Queue\Service\QueueService;

class Mysql extends Config
{
    protected string $queueType = QueueService::QUEUE_TYPE_MYSQL;

    public function beforeSendValidateQueue(): bool
    {
        return env('QUEUE_MYSQL', false);
    }

    public function producer(string $sendTypes, string $templateType, array $params = [], int $delay = 0): bool
    {
        return $this->getQueueMysqlService()->producer($params['id'], $delay);
    }

    private function getQueueMysqlService(): QueueMysqlService
    {
        return $this->biz->getService('Queue:QueueMysql');
    }
}
