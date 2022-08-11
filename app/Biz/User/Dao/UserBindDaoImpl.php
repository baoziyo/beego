<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\User\Dao;

use App\Core\Biz\Dao\Impl\BaseDaoImpl;
use Carbon\Carbon;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\ModelCache\Cacheable;
use Hyperf\Snowflake\Concern\Snowflake;

/**
 * @property int $id
 * @property int $userId
 * @property string $type
 * @property string $fromId
 * @property string $fromKey
 * @property Carbon $createdTime
 * @property Carbon $updatedTime
 * @property Carbon $deletedTime
 */
class UserBindDaoImpl extends BaseDaoImpl
{
    use SoftDeletes;
    use Snowflake;
    use Cacheable;

    protected ?string $table = 'user_bind';

    protected array $fillable = [
        'id', 'userId', 'type', 'fromId', 'fromKey', 'createdTime', 'updatedTime', 'deletedTime',
    ];

    protected array $casts = [
        'id' => 'integer',
        'userId' => 'integer',
        'createdTime' => 'datetime',
        'updatedTime' => 'datetime',
    ];
}
