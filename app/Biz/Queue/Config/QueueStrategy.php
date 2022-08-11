<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Queue\Config;

interface QueueStrategy
{
    public function beforeSendValidateQueue(): bool;

    public function producer(string $sendTypes, string $templateType, array $params = [], int $delay = 0): bool;
}
