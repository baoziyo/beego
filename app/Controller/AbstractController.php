<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Controller;

use App\Biz\Log\Service\LogService;
use App\Core\Biz\Container\Biz;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

abstract class AbstractController
{
    protected ContainerInterface $container;

    protected RequestInterface $request;

    protected ResponseInterface $response;

    protected Biz $biz;

    public function __construct(ContainerInterface $container, RequestInterface $request, ResponseInterface $response, Biz $biz)
    {
        $this->biz = $biz;
        $this->response = $response;
        $this->request = $request;
        $this->container = $container;
    }

    protected function buildRequest(mixed $data = [], string $type = 'json', int $code = 200, string $message = '操作成功.'): PsrResponseInterface
    {
        return $this->response->{$type}([
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'time' => time(),
        ]);
    }

    protected function getLogService(): LogService
    {
        return $this->biz->getService('Log:Log');
    }
}
