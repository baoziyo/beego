<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Queue\Type\Queue;

use App\Biz\Queue\Service\QueueService;
use Hyperf\Amqp\Message\ProducerMessageInterface;

class RabbitMq extends Config
{
    protected string $queueType = QueueService::QUEUE_TYPE_RABBITMQ;

    public function beforeSendValidateQueue(): bool
    {
        return env('QUEUE_AMQP', false);
    }

    public function producer(string $sendTypes, string $templateType, array $params = [], int $delay = 0): bool
    {
        /** @var ProducerMessageInterface $obj */
        $obj = new $templateType($params);
        return $this->biz->getAmqp()->produce($obj);
    }
}
