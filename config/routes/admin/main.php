<?php
/*
 * Sunny 2021/11/30 下午4:56
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

use Hyperf\HttpServer\Router\Router;

Router::addGroup('/admin', function () {
    require_once 'index.php';
});
