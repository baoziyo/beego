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
 * @property string $name 任务名称
 * @property string $queue 队列名称
 * @property string $type 发送通道
 * @property string $template 模版
 * @property array $params 模版参数
 * @property array $sendUserIds 接收用户Ids
 * @property string $status 发送状态: doing 等待发送; finished 发送成功; failed 发送失败;
 * @property int $delay 延迟发送(sec)
 * @property Carbon $createdTime 创建时间
 * @property Carbon $updatedTime 更新时间
 * @method void setName(string $field)
 * @method void setQueue(string $field)
 * @method void setType(string $field)
 * @method void setTemplate(string $field)
 * @method void setParams(array $field)
 * @method void setSendUserIds(array $field)
 * @method void setStatus(string $field)
 * @method void setDelay(int $field)
 */
class QueueDaoImpl extends BaseDaoImpl
{
    use Snowflake;
    use Cacheable;

    protected ?string $table = 'queue';

    protected array $fillable = [
        'id', 'name', 'queue', 'type', 'template', 'params', 'sendUserIds', 'status', 'delay', 'createdTime',
        'updatedTime',
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
