<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Wechat\Exception;

use App\Exception\BaseErrorException;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
class WechatException extends BaseErrorException
{
    /**
     * @Tip("微信appId或appSecret未配置.")
     */
    public const CONFIG_NOT_FOUND = 500003000;

    /**
     * @Tip("微信请求错误:#%s %s.")
     */
    public const CLIENT_ERROR = 500003001;
}
