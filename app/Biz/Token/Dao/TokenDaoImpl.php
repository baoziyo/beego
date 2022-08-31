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
 * @property string $key token 名称
 * @property string $value token 值
 * @property int $expires 有效期(秒)
 * @property Carbon $expiresTime 过期时间
 * @property Carbon $createdTime 创建时间
 */
class TokenDaoImpl extends BaseDaoImpl
{
    use Cacheable;

    public const CREATED_AT = null;

    public const UPDATED_AT = null;

    protected ?string $table = 'token';

    protected array $fillable = [
        'id', 'key', 'value', 'expires', 'expiresTime',
    ];

    protected array $casts = [
        'id' => 'integer',
        'expires' => 'integer',
        'expiresTime' => 'datetime',
        'createdTime' => 'datetime',
    ];
}
