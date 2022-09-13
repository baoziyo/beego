<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Core\Biz\Container;

use App\Biz\User\Dao\UserDaoImpl;
use App\Biz\User\Service\UserService;
use App\Core\Guzzle\Formatter\MessageFormatter;
use App\Core\Guzzle\Middleware\Middleware;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Hyperf\Amqp\Producer;
use Hyperf\Context\Context;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Filesystem\FilesystemFactory;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Guzzle\PoolHandler;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Redis\RedisFactory;
use Hyperf\Redis\RedisProxy;
use Hyperf\Utils\Coroutine;
use League\Flysystem\Filesystem;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;

class BizImpl implements Biz
{
    protected string $serviceDir = 'App\\Biz\\%s%s\\Service\\Impl\\%sServiceImpl';

    private ConfigInterface $config;

    private ContainerInterface $container;

    private Context $context;

    public function __construct(ConfigInterface $config, ContainerInterface $container, Context $context)
    {
        $this->config = $config;
        $this->container = $container;
        $this->context = $context;
    }

    public function getVersion(string $appointVersion = ''): string
    {
        if ($appointVersion === '') {
            $request = make(RequestInterface::class);
            if (!$this->context::get(ServerRequestInterface::class) || !$request->hasHeader('version')) {
                return strtolower((string)env('SYSTEM_API_VERSION', ''));
            }

            return strtolower((string)$request->header('version'));
        }

        return strtolower($appointVersion);
    }

    public function getService(string $serviceName, string $version = ''): mixed
    {
        $version = $this->getVersion($version);
        [$serviceDir, $file] = explode(':', $serviceName);
        $version = $version !== '' ? '\\' . $version : '';
        $class = sprintf($this->serviceDir, $serviceDir, $version, $file);

        if (!class_exists($class)) {
            $class = sprintf($this->serviceDir, $serviceDir, '', $file);
        }

        return make($class);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getRedis(string $poolName = 'default'): RedisProxy
    {
        return $this->getContainer()->get(RedisFactory::class)->get($poolName);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getClient(array $config = [], bool $grayLog = true): Client
    {
        $handler = null;
        if (Coroutine::inCoroutine()) {
            $handler = make(PoolHandler::class, ['option' => ['max_connections' => (int)env('GUZZLE_MAX_CONNECTIONS', 50)]]);
        }
        $handlerStack = HandlerStack::create($handler);
        if ($grayLog === true) {
            $log = Middleware::log($this->getContainer()->get(LoggerFactory::class)->get('guzzle'), new MessageFormatter(MessageFormatter::CLF));
        } else {
            $log = \GuzzleHttp\Middleware::log($this->container->get(LoggerFactory::class)->get('guzzle'), new MessageFormatter(MessageFormatter::CLF));
        }
        $handlerStack->push($log);
        return make(ClientFactory::class)->create(array_merge(['handler' => $handlerStack, 'http_errors' => false], $config));
    }

    public function getCurrentUser(): UserDaoImpl
    {
        return $this->getUserService()->getCurrentUser();
    }

    public function getAmqp(): Producer
    {
        return make(Producer::class);
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }

    public function getFileSystem(string $adapterName = 'local'): Filesystem
    {
        return make(FilesystemFactory::class)->get($adapterName);
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    private function getUserService(): UserService
    {
        return $this->getService('User:User');
    }
}
