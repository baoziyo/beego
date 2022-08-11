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
 * @property array $failUserIds
 * @property int $sendCount
 * @property array $failDetails
 * @property Carbon $createdTime
 * @property Carbon $updatedTime
 */
class QueueFailDaoImpl extends BaseDaoImpl
{
    use Cacheable;

    protected ?string $table = 'queue_fail';

    protected array $fillable = [
        'id', 'failUserIds', 'sendCount', 'failDetails', 'createdTime', 'updatedTime',
    ];

    protected array $casts = [
        'id' => 'integer',
        'failUserIds' => 'array',
        'sendCount' => 'integer',
        'failDetails' => 'array',
        'createdTime' => 'datetime',
        'updatedTime' => 'datetime',
    ];
}
