<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Queue\Config;

use App\Core\Biz\Container\Biz;

abstract class BaseQueueHandle
{
    protected Biz $biz;

    public function __construct(Biz $biz)
    {
        $this->biz = $biz;
    }

    /**
     * @return array[failUserIds,failDetails,...]
     */
    abstract public function handle(array $params): array;
}
