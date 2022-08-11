<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Log\Amqp\Producer;

use App\Core\Amqp\BaseProducer;
use Hyperf\Amqp\Annotation\Producer;
use Hyperf\Utils\Codec\Json;

#[Producer(exchange: 'createGraylog', routingKey: 'createGraylog')]
class CreateGraylogProducer extends BaseProducer
{
    public function __construct(string $level, string $message, array $context)
    {
        $this->payload = Json::encode(['level' => $level, 'message' => $message, 'context' => $context]);
    }
}
