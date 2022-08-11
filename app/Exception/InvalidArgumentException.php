<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Exception;

use Hyperf\Constants\Annotation\Constants;

#[Constants]
class InvalidArgumentException extends BaseErrorException
{
    /**
     * @Tip("参数错误.")
     */
    public const INVALID_ARGUMENT = 500000001;

    /**
     * @Tip("%s 不存在.")
     */
    public const PARAMETER_LOSS = 500000002;

    /**
     * @Tip("%s 必传.")
     */
    public const PARAMETER_MUST_PASS = 500000003;
}
