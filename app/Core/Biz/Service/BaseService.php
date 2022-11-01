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
    /** @var string 开启 */
    public const ENABLED = 'enabled';

    /** @var string 关闭 */
    public const DISABLED = 'disabled';

    /** @var string 申请中 */
    public const DOING = 'doing';

    /** @var string 通过 */
    public const FINISHED = 'finished';

    /** @var string 拒绝 */
    public const FAILED = 'failed';
}
