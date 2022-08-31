<?php
/*
 * Sunny 2022/8/31 上午11:33
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace HyperfTest\Unit\Core\Container;

use App\Biz\Log\Service\LogService;
use App\Core\Biz\Container\Biz;
use App\Core\Biz\Container\BizImpl;
use Hyperf\Amqp\Producer;
use Hyperf\Context\Context;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Redis\RedisProxy;
use Hyperf\Testing\Client;
use Hyperf\Utils\Codec\Json;
use HyperfTest\HttpTestCase;
use League\Flysystem\Filesystem;
use Psr\Container\ContainerInterface;

/**
 * @internal
 */
class BizTest extends HttpTestCase
{
    public function testConstructWhenSuccessThenSuccess(): void
    {
        make(Biz::class);

        $this->assertClassHasAttribute('config', BizImpl::class);
        $this->assertClassHasAttribute('container', BizImpl::class);
        $this->assertClassHasAttribute('context', BizImpl::class);
    }

    public function testGetVersionWhenSuccessThenSuccess(): void
    {
        $client = make(Client::class);
        $result = $client->get('/unit_test/get_version', headers: ['version' => 'v1']);

        $this->assertEquals('v1', $result['data']);
        $this->assertEquals('', $this->biz->getVersion());
        $this->assertEquals('v1', $this->biz->getVersion('V1'));
    }

    public function testGetServiceWhenSuccessThenSuccess(): void
    {
        $this->assertEquals(true, $this->biz->getService('Log:Log') instanceof LogService);
        $this->assertEquals(true, $this->biz->getService('Log:Log', 'V1') instanceof LogService);
    }

    public function testGetRedisWhenSuccessThenSuccess(): void
    {
        $this->assertEquals(true, $this->biz->getRedis() instanceof RedisProxy);
    }

    public function testGetClientWhenSuccessThenSuccess(): void
    {
        $this->assertEquals(true, $this->biz->getClient() instanceof \GuzzleHttp\Client);
        $this->assertEquals(true, $this->biz->getClient(grayLog: false) instanceof \GuzzleHttp\Client);
    }

    public function testGetCurrentUserWhenSuccessThenSuccess(): void
    {
        $this->assertEquals([], $this->biz->getCurrentUser());

        $this->biz->getContext()::set('user', Json::encode(['id' => 1]));
        $this->assertEquals(['id' => 1], $this->biz->getCurrentUser());
    }

    public function testGetAmqpWhenSuccessThenSuccess(): void
    {
        $this->assertEquals(true, $this->biz->getAmqp() instanceof Producer);
    }

    public function testGetContainerWhenSuccessThenSuccess(): void
    {
        $this->assertEquals(true, $this->biz->getContainer() instanceof ContainerInterface);
    }

    public function testGetConfigWhenSuccessThenSuccess(): void
    {
        $this->assertEquals(true, $this->biz->getConfig() instanceof ConfigInterface);
    }

    public function testGetFileSystemWhenSuccessThenSuccess(): void
    {
        $this->assertEquals(true, $this->biz->getFileSystem() instanceof Filesystem);
    }

    public function testGetContextWhenSuccessThenSuccess(): void
    {
        $this->assertEquals(true, $this->biz->getContext() instanceof Context);
    }
}
