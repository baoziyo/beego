<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\File\Exception;

use App\Exception\BaseErrorException;
use Hyperf\Constants\Annotation\Constants;

#[Constants]
class FileException extends BaseErrorException
{
    /**
     * @Tip("无效的文件.")
     */
    public const INVALID_FILE = 500006000;
}
