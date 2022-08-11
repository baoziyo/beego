<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Captcha\Service;

use App\Core\Biz\Service\BaseService;

interface CaptchaService extends BaseService
{
    public const TTL = 7200;

    public function generateCaptcha(): array;

    public function validatorCode(string $code, string $key): bool;
}
