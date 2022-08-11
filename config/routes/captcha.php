<?php
/*
 * Sunny 2021/11/24 下午7:24
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);
use Hyperf\HttpServer\Router\Router;

Router::addGroup('/captcha', function () {
    Router::get('', 'App\Controller\Api\System\CaptchaController@get');
});
