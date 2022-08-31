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
 * @property string $name 用户名
 * @property string $password 密码
 * @property string $salt 密码盐
 * @property int $phone 联系电话
 * @property string $email 邮箱
 * @property int $role 角色id
 * @property string $isAdmin 管理员:enabled 启用;disabled 禁用;
 * @property string $status 状态:enabled 启用;disabled 禁用;
 * @property string $avatar 状态:enabled 启用;disabled 禁用;
 * @property Carbon $lasLoginTime 最后登陆时间
 * @property Carbon $createdTime 创建时间
 * @property Carbon $updatedTime 更新时间
 * @property Carbon $deletedTime 删除时间
 */
class UserDaoImpl extends BaseDaoImpl
{
    use SoftDeletes;
    use Snowflake;
    use Cacheable;

    protected ?string $table = 'user';

    protected array $fillable = [
        'id', 'name', 'password', 'salt', 'phone', 'email', 'role', 'status', 'avatar', 'isAdmin', 'avatar',
        'lasLoginTime',
    ];

    protected array $casts = [
        'id' => 'integer',
        'phone' => 'integer',
        'role' => 'integer',
        'createdTime' => 'datetime',
        'updatedTime' => 'datetime',
        'deletedTime' => 'datetime',
    ];
}
