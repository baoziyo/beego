<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Queue\Service;

use App\Biz\Queue\Dao\QueueDaoImpl;
use App\Biz\Queue\Params\QueueSendParams;
use App\Biz\Queue\Type\Queue\Mysql;
use App\Biz\Queue\Type\Queue\RabbitMq;
use App\Biz\Queue\Type\Queue\Redis;
use App\Core\Biz\Service\BaseService;
use Hyperf\Database\Model\Collection;

interface QueueService extends BaseService
{
    public const QUEUE_TYPE_MYSQL = 'mysql';

    public const QUEUE_TYPE_REDIS = 'redis';

    public const QUEUE_TYPE_RABBITMQ = 'rabbitMq';

    public const SEND_TYPE_MAIL = 'mail';

    public const SEND_TYPE_WECHAT = 'wechat';

    public const SEND_TYPE_LOGICAL = 'logical';

    public const QUEUE_STRATEGY_TYPE = [
        self::QUEUE_TYPE_MYSQL => Mysql::class,
        self::QUEUE_TYPE_REDIS => Redis::class,
        self::QUEUE_TYPE_RABBITMQ => RabbitMq::class,
    ];

    public function get(int $id): QueueDaoImpl|null;

    public function getById(int $id): QueueDaoImpl;

    public function find(array $ids): Collection;

    public function create(QueueDaoImpl $dao): QueueDaoImpl;

    public function createQueue(QueueSendParams $params): bool|array;

    public function failed(int $id, array $failUserIds, array $failDetails): bool;

    public function finished(int $id): bool;

    public function getNotSendUserIds(int $id): array;

    public function deleteByName(string $name): void;
}
