<?php
/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

return [
    'handler' => [
        'http' => [
            Hyperf\HttpServer\Exception\Handler\HttpExceptionHandler::class,
            \App\Exception\Handler\ErrorExceptionHandler::class,
            \Hyperf\ExceptionHandler\Handler\WhoopsExceptionHandler::class,
        ],
    ],
];
