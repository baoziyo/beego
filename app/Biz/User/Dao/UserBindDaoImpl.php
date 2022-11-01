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
 * @property int $userId 用户id
 * @property string $type 绑定类型:wechat 微信;
 * @property string $fromId 来源方用户id
 * @property string $fromKey 来源方用户key
 * @property Carbon $createdTime 创建时间
 * @property Carbon $updatedTime 更新时间
 * @property Carbon $deletedTime 删除时间
 * @method void setUserId(int $field)
 * @method void setType(string $field)
 * @method void setFromId(string $field)
 * @method void setFromKey(string $field)
 */
class UserBindDaoImpl extends BaseDaoImpl
{
    use SoftDeletes;
    use Snowflake;
    use Cacheable;

    protected ?string $table = 'user_bind';

    protected array $fillable = [
        'id', 'userId', 'type', 'fromId', 'fromKey',
    ];

    protected array $casts = [
        'id' => 'integer',
        'userId' => 'integer',
        'createdTime' => 'datetime',
        'updatedTime' => 'datetime',
        'deletedTime' => 'datetime',
    ];
}
