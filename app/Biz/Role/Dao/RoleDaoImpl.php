<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Role\Dao;

use App\Core\Biz\Dao\Impl\BaseDaoImpl;
use Carbon\Carbon;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\ModelCache\Cacheable;
use Hyperf\Snowflake\Concern\Snowflake;

/**
 * @property int $id
 * @property string $name
 * @property string $status
 * @property array $data
 * @property Carbon $createdTime
 * @property Carbon $updatedTime
 * @property Carbon $deletedTime
 */
class RoleDaoImpl extends BaseDaoImpl
{
    use SoftDeletes;
    use Snowflake;
    use Cacheable;

    protected ?string $table = 'role';

    protected array $fillable = [
        'id', 'name', 'status', 'data',
    ];

    protected array $casts = [
        'id' => 'integer',
        'data' => 'array',
        'createdTime' => 'datetime',
        'updatedTime' => 'datetime',
        'deletedTime' => 'datetime',
    ];
}
