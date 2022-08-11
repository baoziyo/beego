<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Log\Crontab;

use App\Core\Crontab\BaseCrontab;
use Hyperf\Crontab\Annotation\Crontab;

#[Crontab(rule: '50 23 * * *', name: 'deleteOldFileLogs', callback: 'execute', memo: '清理旧日志')]
class DeleteOldFileLogsCrontab extends BaseCrontab
{
    protected function execute(): bool
    {
        return true;
    }
}
