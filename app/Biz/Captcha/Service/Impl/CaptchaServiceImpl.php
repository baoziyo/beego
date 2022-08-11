<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Captcha\Service\Impl;

use App\Biz\Captcha\Exception\CaptchaException;
use App\Biz\Captcha\Service\CaptchaService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;
use Baoziyoo\HyperfCaptcha\Captcha;
use Hyperf\Utils\Str;
use Psr\SimpleCache\InvalidArgumentException;

class CaptchaServiceImpl extends BaseServiceImpl implements CaptchaService
{
    /**
     * @throws InvalidArgumentException
     */
    public function generateCaptcha(): array
    {
        $captcha = new Captcha();
        $captcha = $captcha->generateCode();
        $key = Str::random(64);
        $this->biz->getRedis()->set($key, $captcha['code'], self::TTL);
        return ['base64' => $captcha['base64'], 'key' => $key];
    }

    /**
     * @throws InvalidArgumentException
     */
    public function validatorCode(string $code, string $key): bool
    {
        if (!$this->biz->getRedis()->has($key)) {
            throw new CaptchaException(CaptchaException::CAPTCHA_EMPTY);
        }
        return $this->biz->getRedis()->get($key) === $code;
    }
}
