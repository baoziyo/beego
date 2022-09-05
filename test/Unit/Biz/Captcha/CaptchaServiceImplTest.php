<?php

/*
 * Sunny 2022/8/31 上午11:12
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace HyperfTest\Unit\Biz\Captcha;

use App\Biz\Captcha\Exception\CaptchaException;
use App\Biz\Captcha\Service\CaptchaService;
use Hyperf\Utils\Str;
use HyperfTest\HttpTestCase;

/**
 * @internal
 */
class CaptchaServiceImplTest extends HttpTestCase
{
    public function testGenerateCaptchaWhenSuccessThenSuccess(): void
    {
        $captcha = $this->getCaptchaService()->generateCaptcha();

        $this->assertEquals(false, $this->biz->getRedis()->get($captcha['key']) === false);
        $this->assertEquals(true, array_key_exists('base64', $captcha));
        $this->assertEquals(true, array_key_exists('key', $captcha));
    }

    public function testValidatorCodeWhenSuccessThenSuccess(): void
    {
        $captcha = $this->getCaptchaService()->generateCaptcha();
        $code = $this->biz->getRedis()->get($captcha['key']);
        $response = $this->getCaptchaService()->validatorCode($code, $captcha['key']);

        $this->assertEquals(true, $response);
        $this->assertEquals(false, $this->biz->getRedis()->get($captcha['key']));

        $captcha = $this->getCaptchaService()->generateCaptcha();
        $response = $this->getCaptchaService()->validatorCode('', $captcha['key']);

        $this->assertEquals(false, $response);
    }

    public function testValidatorCodeWhenErrorCodeThenThrowError(): void
    {
        $this->expectException(CaptchaException::class);
        $this->expectExceptionCode(CaptchaException::CAPTCHA_EMPTY);

        $this->getCaptchaService()->validatorCode('', Str::random(64));
    }

    private function getCaptchaService(): CaptchaService
    {
        return $this->biz->getService('Captcha:Captcha');
    }
}
