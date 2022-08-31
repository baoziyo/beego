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
 * @property string $name 菜单名称
 * @property string $status 状态:enabled 启用;disabled 禁用;
 * @property string $link 路由
 * @property string $type 类型:module 模块;controller 控制器;node 节点;option 操作;
 * @property int $parentId 父id
 * @property string $module 模块
 * @property string $controller 控制器
 * @property string $node 节点
 * @property string $option 操作
 * @property string $style 样式
 * @property string $icon 图标
 * @property int $sort 排序
 * @property Carbon $createdTime 创建时间
 * @property Carbon $updatedTime 更新时间
 * @property Carbon $deletedTime 删除时间
 */
class RoleRbacNodeDaoImpl extends BaseDaoImpl
{
    use SoftDeletes;
    use Snowflake;
    use Cacheable;

    protected ?string $table = 'role_rbac_node';

    protected array $fillable = [
        'id', 'name', 'status', 'link', 'type', 'parentId', 'module', 'controller', 'node', 'option', 'style',
        'icon', 'sort',
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
