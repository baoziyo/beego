<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare (strict_types=1);

namespace App\Biz\Role\Exception;

use App\Exception\BaseErrorException;
use Hyperf\Constants\Annotation\Constants;

#[Constants]
class RoleException extends BaseErrorException
{
    /**
     * @Tip("找不到该权限组.")
     */
    public const NOT_FOUND = 500002000;
    /**
     * @Tip("暂无此操作权限.")
     */
    public const NOT_PERMISSION = 500002001;
}