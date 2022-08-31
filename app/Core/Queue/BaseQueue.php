<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Core\Queue;

use App\Biz\Queue\Service\QueueService;
use App\Core\Biz\Container\Biz;
use App\Utils\ErrorTools;
use Hyperf\AsyncQueue\Job;
use Throwable;

abstract class BaseQueue extends Job
{
    protected Biz $biz;

    protected int $maxAttempts = 3;

    protected array $params;

    public function __construct(Biz $biz, array $params)
    {
        $this->params = $params;
        $this->biz = $biz;
    }

    /**
     * @throws Throwable
     */
    public function handle(): bool
    {
        try {
            $this->params['sendUserIds'] = $this->getQueueService()->getNotSendUserIds($this->params['id']);
            $response = $this->process($this->params);
            if (!empty($response['failUserIds'])) {
                $this->getQueueService()->failed($response['id'], $response['failUserIds'], $response['failDetails']);
            } else {
                $this->getQueueService()->finished($response['id']);
            }
        } catch (Throwable $e) {
            $this->getQueueService()->failed($this->params['id'], ['all'], ErrorTools::generateErrorInfo($e));
            throw new $e();
        }
        return true;
    }

    /**
     * @return array[id,failUserIds,failDetails,...]
     */
    abstract public function process(array $params): array;

    private function getQueueService(): QueueService
    {
        return $this->biz->getService('Queue:Queue');
    }
}
