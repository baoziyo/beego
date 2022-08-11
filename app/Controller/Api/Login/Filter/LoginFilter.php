<?php

declare(strict_types=1);

namespace App\Controller\Api\Login\Filter;

use App\Annotation\ResponseFilter\Filter;

class LoginFilter extends Filter
{
    protected array $fields = [
        'id', 'avatar', 'isAdmin', 'name', 'refreshToken', 'token', 'type', 'roleName', 'roleCode',
    ];
}