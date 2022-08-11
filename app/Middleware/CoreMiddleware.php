<?php
/*
 * Sunny 2021/11/24 下午5:38
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

namespace App\Middleware;

use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Codec\Json;
use Psr\Http\Message\ServerRequestInterface;

class CoreMiddleware extends \Hyperf\HttpServer\CoreMiddleware
{
    /**
     * Handle the response when cannot found any routes.
     */
    protected function handleNotFound(ServerRequestInterface $request): mixed
    {
        // 重写路由找不到的处理逻辑
        $data = Json::encode([
            'code' => 404,
            'message' => '找不到该地址.',
            'data' => null,
            'time' => time(),
        ]);

        return $this->response()->withStatus(404)->withAddedHeader('Content-Type', 'application/json')->withBody(new SwooleStream($data));
    }

    /**
     * Handle the response when the routes found but doesn't match any available methods.
     */
    protected function handleMethodNotAllowed(array $methods, ServerRequestInterface $request): mixed
    {
        // 重写 HTTP 方法不允许的处理逻辑
        $data = Json::encode([
            'code' => 404,
            'message' => '找不到该地址.',
            'data' => null,
            'time' => time(),
        ]);

        return $this->response()->withStatus(404)->withAddedHeader('Content-Type', 'application/json')->withBody(new SwooleStream($data));
    }
}
