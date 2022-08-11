<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Token\Dao;

use App\Core\Biz\Dao\Impl\BaseDaoImpl;
use Carbon\Carbon;
use Hyperf\ModelCache\Cacheable;

/**
 * @property int $id
 * @property string $key
 * @property string $value
 * @property int $expires
 * @property Carbon $expiresTime
 * @property Carbon $createdTime
 */
class TokenDaoImpl extends BaseDaoImpl
{
    use Cacheable;

    public const CREATED_AT = null;

    public const UPDATED_AT = null;

    protected ?string $table = 'token';

    protected array $fillable = [
        'id', 'key', 'value', 'expires', 'expiresTime', 'createdTime',
    ];

    protected array $casts = [
        'id' => 'integer',
        'expires' => 'integer',
        'expiresTime' => 'datetime',
        'createdTime' => 'datetime',
    ];
}
