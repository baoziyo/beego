<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Core\Biz\Service;

interface BaseService
{
    public const ENABLED = 'enabled';

    public const DISABLED = 'disabled';

    public const DOING = 'doing';

    public const FINISHED = 'finished';

    public const FAILED = 'failed';
}
