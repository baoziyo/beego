<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare (strict_types=1);

namespace App\Biz\Log\Service\Impl;

use App\Biz\Log\Amqp\Producer\CreateGraylogProducer;
use App\Biz\Log\Service\LogService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\Codec\Json;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class LogServiceImpl extends BaseServiceImpl implements LogService
{
    protected LoggerInterface $logger;

    public function emergency($message, array $context = [], bool $sendGaryLog = true): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context, $sendGaryLog);
    }

    public function alert($message, array $context = [], bool $sendGaryLog = true): void
    {
        $this->log(LogLevel::ALERT, $message, $context, $sendGaryLog);
    }

    public function critical($message, array $context = [], bool $sendGaryLog = true): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context, $sendGaryLog);
    }

    public function error($message, array $context = [], bool $sendGaryLog = true): void
    {
        $this->log(LogLevel::ERROR, $message, $context, $sendGaryLog);
    }

    public function warning($message, array $context = [], bool $sendGaryLog = true): void
    {
        $this->log(LogLevel::WARNING, $message, $context, $sendGaryLog);
    }

    public function notice($message, array $context = [], bool $sendGaryLog = true): void
    {
        $this->log(LogLevel::NOTICE, $message, $context, $sendGaryLog);
    }

    public function info($message, array $context = [], bool $sendGaryLog = true): void
    {
        $this->log(LogLevel::INFO, $message, $context, $sendGaryLog);
    }

    public function debug($message, array $context = [], bool $sendGaryLog = true): void
    {
        $this->log(LogLevel::DEBUG, $message, $context, $sendGaryLog);
    }

    public function log($level, $message, array $context = [], bool $sendGaryLog = true): void
    {
        $this->logger = $this->container->get(LoggerFactory::class)->get(env('APP_NAME'));
        $this->logger->log($level, $message, $context);
        if ($sendGaryLog && env('GRAYLOG', false)) {
            $message = new CreateGraylogProducer($level, $message, $context);
            $this->biz->getAmqp()->produce($message);
        }
    }

    public function requestLog(RequestInterface $request, ResponseInterface $response, array $responseText = []): void
    {
        $text = PHP_EOL . 'url:' . $request->getMethod() . '->' . $request->url() . PHP_EOL;
        $text .= 'serverParams:' . Json::encode($request->getServerParams()) . PHP_EOL;
        $text .= 'header:' . Json::encode($request->getHeaders()) . PHP_EOL;
        $text .= 'params:' . Json::encode($request->getQueryParams()) . PHP_EOL;
        $text .= 'body:' . Json::encode($request->getParsedBody()) . PHP_EOL;
        $text .= 'response:' . $response->getBody()->getContents() . PHP_EOL;
        if ((bool)env('GRAYLOG', false)) {
            $message = new CreateGraylogProducer('info', '请求日志:' . $request->getMethod() . '->' . $request->url(), ['method' => $request->getMethod(), 'url' => $request->url(), 'serverParams' => $request->getServerParams(), 'headers' => $request->getHeaders(), 'queryParams' => $request->getQueryParams(), 'parsedBody' => $request->getParsedBody(), 'contents' => $response->getBody()->getContents()]);
            $this->biz->getAmqp()->produce($message);
        }
        $this->info($text, $responseText);
    }

    public function createGraylog(string $level, string $message, array $context = []): void
    {
        if (env('GRAYLOG', false)) {
            $this->biz->getClient([], false)->post(env('GRAYLOG_DOMAIN', 'http://127.0.0.1') . ':' . env('GRAYLOG_PORT', 12201) . '/gelf', ['json' => ['level' => self::GRAY_LOG_LEVELS[$level], 'version' => env('SYSTEM_API_VERSION', 'v1'), 'source' => env('GRAYLOG_SOURCE') ?? gethostname(), 'log_source' => env('APP_NAME') . '_' . env('APP_ENV'), 'full_message' => Json::encode($context), 'message' => $message . (!empty($context['target']) ? '->#' . $context['target'] : '')]]);
        }
    }
}