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
 * @property string $name
 * @property string $password
 * @property string $salt
 * @property int $phone
 * @property string $email
 * @property int $role
 * @property string $status
 * @property Carbon $lasLoginTime
 * @property Carbon $createdTime
 * @property Carbon $updatedTime
 * @property Carbon $deletedTime
 */
class UserDaoImpl extends BaseDaoImpl
{
    use SoftDeletes;
    use Snowflake;
    use Cacheable;

    protected ?string $table = 'user';

    protected array $fillable = [
        'id', 'name', 'password', 'salt', 'phone', 'email', 'role', 'status', 'isAdmin', 'avatar', 'lasLoginTime',
    ];

    protected array $casts = [
        'id' => 'integer',
        'phone' => 'integer',
        'role' => 'integer',
    ];
}
