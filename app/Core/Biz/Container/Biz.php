<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Core\Biz\Container;

use App\Biz\User\Dao\UserDaoImpl;
use GuzzleHttp\Client;
use Hyperf\Amqp\Producer;
use Hyperf\Context\Context;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Redis\RedisProxy;
use League\Flysystem\Filesystem;
use Psr\Container\ContainerInterface;

interface Biz
{
    public function getVersion(string $appointVersion = ''): string;

    public function getService(string $serviceName, string $version = ''): mixed;

    public function getRedis(string $poolName = 'default'): RedisProxy;

    public function getClient(array $config = [], bool $grayLog = true): Client;

    public function getCurrentUser(): UserDaoImpl;

    public function getAmqp(): Producer;

    public function getContainer(): ContainerInterface;

    public function getConfig(): ConfigInterface;

    public function getFileSystem(string $adapterName = 'local'): Filesystem;

    public function getContext(): Context;
}
