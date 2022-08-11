<?php
/*
 * Sunny 2021/11/24 下午7:25
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Controller\Api\System;

use App\Biz\Captcha\Service\CaptchaService;
use App\Controller\AbstractController;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class CaptchaController extends AbstractController
{
    public function get(): PsrResponseInterface
    {
        $response = $this->getCaptchaService()->generateCaptcha();

        return $this->buildRequest($response);
    }

    private function getCaptchaService(): CaptchaService
    {
        return $this->biz->getService('Captcha:Captcha');
    }
}
