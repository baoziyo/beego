<?php
/*
 * Sunny 2022/10/13 下午5:06
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Biz\Queue\Params;

use App\Core\BaseParams;

class QueueSendParams extends BaseParams
{
    /** @var string 队列类型 */
    public string $queueType;

    /** @var array 发送类型 */
    public array $sendTypes;

    /** @var string 发送模版 */
    public string $template;

    /** @var array 发送用户 */
    public array $userIds = [];

    /** @var array 额外参数 */
    public array $params = [];

    /** @var int 延迟发送(秒) */
    public int $delay = 0;

    /** @var string 队列名称 */
    public string $name = '';

    public function handelData(): void
    {
    }
}
