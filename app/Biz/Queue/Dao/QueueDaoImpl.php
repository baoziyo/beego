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
use Hyperf\Snowflake\Concern\Snowflake;

/**
 * @property int $id
 * @property string $queue
 * @property string $type
 * @property string $template
 * @property array $params
 * @property array $sendUserIds
 * @property string $status
 * @property int $delay
 * @property Carbon $createdTime
 * @property Carbon $updatedTime
 */
class QueueDaoImpl extends BaseDaoImpl
{
    use Snowflake;
    use Cacheable;

    protected ?string $table = 'queue';

    protected array $fillable = [
        'id', 'queue', 'type', 'template', 'params', 'sendUserIds', 'status', 'delay', 'createdTime', 'updatedTime',
    ];

    protected array $casts = [
        'id' => 'integer',
        'params' => 'array',
        'sendUserIds' => 'array',
        'delay' => 'integer',
        'createdTime' => 'datetime',
        'updatedTime' => 'datetime',
    ];
}
