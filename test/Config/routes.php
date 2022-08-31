<?php
/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

use Hyperf\HttpServer\Router\Router;

Router::addGroup('/unit_test', static function () {
    Router::addGroup('/annotation/filter', static function () {
        Router::get('/simple', 'HyperfTest\Controller\AnnotationFilterController@simple');
        Router::get('/complex', 'HyperfTest\Controller\AnnotationFilterController@complex');
        Router::get('/complex2', 'HyperfTest\Controller\AnnotationFilterController@complex2');
        Router::get('/id_to_string', 'HyperfTest\Controller\AnnotationFilterController@idToString');
    });

    Router::get('/get_version', 'HyperfTest\Controller\BizController@getVersion');
});
