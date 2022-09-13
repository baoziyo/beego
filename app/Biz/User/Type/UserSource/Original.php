<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\User\Type\UserSource;

use App\Biz\Captcha\Service\CaptchaService;
use App\Biz\User\Config\BaseUserSource;
use App\Biz\User\Exception\UserException;
use App\Biz\User\Service\UserService;

class Original extends BaseUserSource
{
    public function buildRegisterParams(array $params): array
    {
        return $params;
    }

    public function handleLogin(array $params): int
    {
        $codeStatus = $this->getCaptchaService()->validatorCode($params['code'], $params['key']);
        if ($codeStatus === false) {
            throw new UserException(UserException::CAPTCHA_FIELD_ERROR);
        }

        $user = $this->getUserService()->getByName($params['name']);
        [$password] = $this->getUserService()->generatePassword($params['password'], $user->salt);
        if ($user->password !== $password) {
            throw new UserException(UserException::USER_PASSWORD_FIELD_ERROR);
        }

        return $user->id;
    }

    private function getUserService(): UserService
    {
        return $this->biz->getService('User:User');
    }

    private function getCaptchaService(): CaptchaService
    {
        return $this->biz->getService('Captcha:Captcha');
    }
}
