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
 * @property string $name 权限名称
 * @property string $status 状态:enabled 启用;disabled 禁用;
 * @property array $data 权限配置
 * @property Carbon $createdTime 创建时间
 * @property Carbon $updatedTime 更新时间
 * @property Carbon $deletedTime 删除时间
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
