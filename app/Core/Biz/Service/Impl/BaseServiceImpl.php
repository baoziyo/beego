<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Core\Biz\Service\Impl;

use App\Biz\Log\Service\LogService;
use App\Core\Biz\Container\Biz;
use App\Core\Biz\Service\BaseService;
use Psr\Container\ContainerInterface;

abstract class BaseServiceImpl implements BaseService
{
    protected Biz $biz;

    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container, Biz $biz)
    {
        $this->biz = $biz;
        $this->container = $container;
    }

    protected function buildConditions(array $conditions): array
    {
        return $conditions;
    }

    protected function getLogService(): LogService
    {
        return $this->biz->getService('Log:Log');
    }
}
