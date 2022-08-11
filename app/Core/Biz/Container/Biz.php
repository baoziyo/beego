<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Core\Biz\Container;

use GuzzleHttp\Client;
use Hyperf\Amqp\Producer;
use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;
use League\Flysystem\Filesystem;

interface Biz
{
    public function getVersion(string $appointVersion = ''): string;

    public function getService(string $serviceName, string $version = '');

    public function getRedis(string $poolName = 'default'): CacheInterface;

    public function getClient(array $config = [], bool $grayLog = true): Client;

    public function getCurrentUser(): array;

    public function getAmqp(): Producer;

    public function getContainer(): ContainerInterface;

    public function getConfig(): ConfigInterface;

    public function getFileSystem(string $adapterName = 'local'): Filesystem;
}
