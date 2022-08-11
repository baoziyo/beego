<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\User\Type\UserSource;

use App\Biz\User\Config\BaseUserSource;

class Original extends BaseUserSource
{
    public function buildRegisterParams(array $params): array
    {
        return $params;
    }

    public function handleLogin(array $params): int
    {
        return 0;
    }
}
