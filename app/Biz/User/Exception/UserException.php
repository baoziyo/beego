<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\User\Exception;

use App\Exception\BaseErrorException;
use Hyperf\Constants\Annotation\Constants;

#[Constants]
class UserException extends BaseErrorException
{
    /**
     * @Tip("类型错误.")
     */
    public const TOKEN_TYPE_ERROR = 500004000;

    /**
     * @Tip("注册用户失败.")
     */
    public const REGISTER_USER_ERROR = 500004001;

    /**
     * @Tip("注册来源不存在.")
     */
    public const USER_SOURCE_ERROR = 500004002;

    /**
     * @Tip("找不到该登陆用户.")
     */
    public const NOT_FOUND_LOGIN_USER = 500004003;
}
