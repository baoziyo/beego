<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\User\Config;

interface TokenStrategy
{
    public const EXPIRES_TIME = 36000;

    public const REFRESH_EXPIRES_TIME = 72000;

    public function generateToken(int $userId): array;

    public function refreshToken(string $refreshToken): array;

    public function validate(string $token): int;
}
