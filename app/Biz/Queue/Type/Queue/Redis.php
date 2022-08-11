<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Queue\Type\Queue;

use App\Biz\Queue\Service\QueueService;
use Hyperf\AsyncQueue\Driver\DriverInterface;
use Hyperf\AsyncQueue\JobInterface;
use Hyperf\Di\Annotation\Inject;

class Redis extends Config
{
    /**
     * @Inject
     */
    protected DriverInterface $driver;

    protected string $queueType = QueueService::QUEUE_TYPE_REDIS;

    public function beforeSendValidateQueue(): bool
    {
        return env('QUEUE_REDIS', false);
    }

    public function producer(string $sendTypes, string $templateType, array $params = [], int $delay = 0): bool
    {
        /** @var JobInterface $obj */
        $obj = new $templateType($params);
        return $this->driver->push($obj, $delay);
    }
}
