<?php
/*
 * Sunny 2022/8/26 下午6:18
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Listener;

use Dotenv\Dotenv;
use Dotenv\Repository\Adapter;
use Dotenv\Repository\RepositoryBuilder;
use Hyperf\Config\ProviderConfig;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BootApplication;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Finder\Finder;

#[Listener]
class MultiEnvListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            BootApplication::class,
        ];
    }

    /**
     * @param BootApplication $event
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function process(object $event): void
    {
        if (defined('CURRENT_ENV') && CURRENT_ENV === 'test' && file_exists(BASE_PATH . '/.env.test') && ApplicationContext::hasContainer()) {
            $container = ApplicationContext::getContainer();
            $config = $container->get(ConfigInterface::class);
            $newConfig = $this->merge('test');

            foreach ($newConfig as $key => $value) {
                $config->set($key, $value);
            }
        }
    }

    public function merge(string $env): array
    {
        if (file_exists(BASE_PATH . '/.env.' . $env)) {
            $repository = RepositoryBuilder::createWithNoAdapters()
                ->addReader(Adapter\PutenvAdapter::class)
                ->addWriter(Adapter\PutenvAdapter::class)
                ->make();
            Dotenv::create($repository, [BASE_PATH], '.env.' . $env)->load();
        }

        $configPath = BASE_PATH . '/config/';
        $config = $this->readConfig($configPath . 'config.php');
        $serverConfig = $this->readConfig($configPath . 'server.php');
        $autoloadConfig = $this->readPaths([BASE_PATH . '/config/autoload']);

        return array_merge_recursive(ProviderConfig::load(), $serverConfig, $config, ...$autoloadConfig);
    }

    private function readConfig(string $configPath): array
    {
        $config = [];
        if (file_exists($configPath) && is_readable($configPath)) {
            $config = require $configPath;
        }
        return is_array($config) ? $config : [];
    }

    private function readPaths(array $paths): array
    {
        $configs = [];
        $finder = new Finder();
        $finder->files()->in($paths)->name('*.php');
        foreach ($finder as $file) {
            $configs[] = [
                $file->getBasename('.php') => require $file->getRealPath(),
            ];
        }

        return $configs;
    }
}
