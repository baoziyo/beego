<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Queue\Exception;

use App\Exception\BaseErrorException;
use Hyperf\Constants\Annotation\Constants;

#[Constants]
class QueueException extends BaseErrorException
{
    /**
     * @Tip("找不到该通知队列.")
     */
    public const NOT_FUND_QUEUE = 500005000;

    /**
     * @Tip("%s 该通道未开启或未配置.")
     */
    public const QUEUE_TYPE_DISABLED = 500005001;

    /**
     * @Tip("找不到该通知消息# %s.")
     */
    public const NOT_FUND_QUEUE_JOB = 500005001;
}
