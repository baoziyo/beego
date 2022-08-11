<?php

declare(strict_types=1);

namespace App\Listener;

use App\Core\Biz\Container\Biz;
use Hyperf\Database\Events\QueryExecuted;
use Hyperf\Database\Events\StatementPrepared;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Str;
use Psr\Container\ContainerInterface;

#[Listener]
class DbQueryExecutedListener implements ListenerInterface
{
    protected Biz $biz;

    protected ContainerInterface $container;

    public function __construct(Biz $biz, ContainerInterface $container)
    {
        $this->biz = $biz;
        $this->container = $container;
    }

    public function listen(): array
    {
        return [
            QueryExecuted::class,
            StatementPrepared::class,
        ];
    }

    /**
     * @param QueryExecuted $event
     */
    public function process(object $event)
    {
        if ($event instanceof QueryExecuted) {
            $sql = $event->sql;
            if (!Arr::isAssoc($event->bindings)) {
                foreach ($event->bindings as $key => $value) {
                    $sql = Str::replaceFirst('?', "'{$value}'", $sql);
                }
            }

            $logger = $this->container->get(LoggerFactory::class)->get('sql');
            $logger->info(sprintf('[%s] sql: %s', $event->time, $sql));

            if ($event->time > (int)env('LOW_SQL_TIME', 3000)) {
                $logger = $this->container->get(LoggerFactory::class)->get('low-sql');
                $logger->info(sprintf('[%s] sql慢查询: %s', $event->time, $sql));
            }
        }

        if ($event instanceof StatementPrepared) {
            $event->statement->setFetchMode(\PDO::FETCH_ASSOC);
        }
    }
}
