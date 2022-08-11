<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\User\Validation;

use App\Core\Validation\BaseValidation;

class TokenValidation extends BaseValidation
{
    protected array $rules = [
        'userName' => 'required',
        'password' => 'required',
    ];

    protected array $scene = [
        'jwt' => ['userName', 'password']
    ];
}
