<?php
/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

use Hyperf\HttpServer\Router\Router;

if (env('APP_ENV') === 'dev') {
    Router::addGroup('/test', static function () {
        Router::get('{id}', 'App\Controller\TestController@get');
        Router::get('', 'App\Controller\TestController@search');
        Router::post('', 'App\Controller\TestController@create');
        Router::put('', 'App\Controller\TestController@update');
        Router::patch('', 'App\Controller\TestController@patch');
        Router::delete('', 'App\Controller\TestController@delete');
    });
}

Router::addGroup('', static function () {
    require_once 'routes/login.php';
    require_once 'routes/captcha.php';
    require_once 'routes/register.php';
});
