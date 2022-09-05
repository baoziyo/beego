<?php
/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

return [
    Hyperf\HttpServer\CoreMiddleware::class => App\Middleware\CoreMiddleware::class,
    \App\Core\Biz\Container\Biz::class => \App\Core\Biz\Container\BizImpl::class,
    Hyperf\Contract\StdoutLoggerInterface::class => \App\Core\Log\Factory\StdoutLoggerFactory::class,
    Hyperf\Validation\Contract\ValidatorFactoryInterface::class => \App\Core\Validation\Factory\ValidatorFactory::class,
];
