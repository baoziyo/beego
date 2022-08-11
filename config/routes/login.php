<?php
/*
 * Sunny 2021/11/24 下午7:24
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

use Hyperf\HttpServer\Router\Router;

Router::addGroup('/login', function () {
    Router::post('', 'App\Controller\Api\Login\LoginController@post');
    Router::put('', 'App\Controller\Api\Login\LoginController@update');

    Router::addGroup('/wechat', function () {
        Router::post('', 'App\Controller\Api\Login\WechatLoginController@post');
    });
});
