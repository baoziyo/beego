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
 * @property string $link
 * @property string $type
 * @property int $parentId
 * @property string $module
 * @property string $controller
 * @property string $node
 * @property string $option
 * @property string $style
 * @property string $icon
 * @property int $sort
 * @property Carbon $createdTime
 * @property Carbon $updatedTime
 * @property Carbon $deletedTime
 */
class RoleRbacNodeDaoImpl extends BaseDaoImpl
{
    use SoftDeletes;
    use Snowflake;
    use Cacheable;

    protected ?string $table = 'role_rbac_node';

    protected array $fillable = [
        'id', 'name', 'status', 'link', 'type', 'parentId', 'module', 'controller', 'node', 'option', 'style',
        'icon', 'sort', 'createdTime', 'updatedTime', 'deletedTime',
    ];

    protected array $casts = [
        'id' => 'integer',
        'parentId' => 'integer',
        'sort' => 'integer',
        'createdTime' => 'datetime',
        'updatedTime' => 'datetime',
        'deletedTime' => 'datetime',
    ];
}
