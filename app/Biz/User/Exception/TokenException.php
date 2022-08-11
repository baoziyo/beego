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
class TokenException extends BaseErrorException
{
    /**
     * @Tip("token类型错误.")
     */
    public const TOKEN_TYPE_ERROR = 403001000;

    /**
     * @Tip("JWT密钥未配置.")
     */
    public const JWT_KEY_EMPTY = 403001001;

    /**
     * @Tip("token过期")
     */
    public const TOKEN_EXPIRED = 403001002;

    /**
     * @Tip("token错误")
     */
    public const TOKEN_ERROR = 403001003;

    /**
     * @Tip("token为空")
     */
    public const TOKEN_EMPTY = 403001004;
}
