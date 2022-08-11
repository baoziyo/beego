<?php
/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

$extends = [];

if (env('QUEUE_REDIS', false) === true) {
    $extends[] = Hyperf\AsyncQueue\Process\ConsumerProcess::class;
}

return array_merge([
    \Hyperf\Crontab\Process\CrontabDispatcherProcess::class,
], $extends);
