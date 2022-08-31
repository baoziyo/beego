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
 * @property Carbon $sendTime 发送时间
 * @property Carbon $createdTime 创建时间
 * @property Carbon $updatedTime 更新时间
 */
class QueueMysqlDaoImpl extends BaseDaoImpl
{
    use Cacheable;

    protected ?string $table = 'queue_mysql';

    protected array $fillable = [
        'id', 'sendTime',
    ];

    protected array $casts = [
        'id' => 'integer',
        'sendTime' => 'datetime',
        'createdTime' => 'datetime',
        'updatedTime' => 'datetime',
    ];
}
