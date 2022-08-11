<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Core\Amqp;

use App\Core\Biz\Container\Biz;
use Hyperf\Amqp\Message\ConsumerMessage;

abstract class BaseConsumer extends ConsumerMessage
{
    protected Biz $biz;

    public function __construct(Biz $biz)
    {
        $this->biz = $biz;
    }

    public function isEnable(): bool
    {
        return (bool)env('QUEUE_AMQP', false);
    }
}
