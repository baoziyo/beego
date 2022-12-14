<?php

/*
 * Sunny 2022/8/9 上午11:58
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Queue\Dao;

use App\Core\Biz\Dao\Impl\BaseDaoImpl;
use Carbon\Carbon;
use Hyperf\ModelCache\Cacheable;

/**
 * @property int $id
 * @property int $targetId 通知id
 * @property array $failUserIds 发送失败用户Ids
 * @property int $sendCount 发送次数
 * @property array $failDetails 失败详情
 * @property Carbon $createdTime 创建时间
 * @property Carbon $updatedTime 更新时间
 * @method void setTargetId(int $field)
 * @method void setFailUserIds(array $field)
 * @method void setSendCount(int $field)
 * @method void setFailDetails(array $field)
 */
class QueueFailDaoImpl extends BaseDaoImpl
{
    use Cacheable;

    protected ?string $table = 'queue_fail';

    protected array $fillable = [
        'id', 'targetId', 'failUserIds', 'sendCount', 'failDetails',
    ];

    protected array $casts = [
        'id' => 'integer',
        'targetId' => 'integer',
        'failUserIds' => 'array',
        'sendCount' => 'integer',
        'failDetails' => 'array',
        'createdTime' => 'datetime',
        'updatedTime' => 'datetime',
    ];
}
