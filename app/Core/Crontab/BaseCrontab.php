<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Core\Crontab;

use App\Biz\Log\Service\LogService;
use App\Core\Biz\Container\Biz;

abstract class BaseCrontab
{
    protected Biz $biz;

    public function __construct(Biz $biz)
    {
        $this->biz = $biz;
    }

    protected function getLogService(): LogService
    {
        return $this->biz->getService('Log:Log');
    }
}
