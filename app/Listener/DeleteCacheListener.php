<?php

declare(strict_types=1);

namespace App\Listener;

use Hyperf\Database\Model\Events\Event;
use Hyperf\Event\Annotation\Listener;
use Hyperf\ModelCache\InvalidCacheManager;

#[Listener]
class DeleteCacheListener extends \Hyperf\ModelCache\Listener\DeleteCacheListener
{
    public function process(object $event): void
    {
        if (!$event instanceof Event) {
            return;
        }

        $model = $event->getModel();
        if (method_exists($model, 'deleteCache')) {
            $model->deleteCache();
            return;
        }

        if ($model->getConnection()->transactionLevel() > 0) {
            InvalidCacheManager::instance()->push($model);
            return;
        }

        $model->deleteCache();
    }
}
