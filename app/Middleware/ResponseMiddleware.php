<?php
/*
 * Sunny 2021/11/24 下午5:38
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Middleware;

use App\Annotation\ResponseFilter\ResponseFilterAnnotations;
use App\Core\Biz\Container\Biz;
use Doctrine\Common\Annotations\AnnotationReader;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ResponseMiddleware implements MiddlewareInterface
{
    protected ContainerInterface $container;

    protected RequestInterface $request;

    protected HttpResponse $response;

    /**
     * @Inject
     */
    protected Biz $biz;

    public function __construct(ContainerInterface $container, HttpResponse $response, RequestInterface $request)
    {
        $this->container = $container;
        $this->response = $response;
        $this->request = $request;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        if ($response->getStatusCode() === 200) {
            $response = $this->annotationReader($request, $response);
        }

        $this->biz->getService('Log:Log')->requestLog($this->request, $response);

        return $response;
    }

    private function annotationReader(ServerRequestInterface $request, $response)
    {
        $content = $response->getBody()->getContents();
        [$className, $actionName] = $this->getClassAndAction($request);
        if (! $className || ! $actionName) {
            return $response;
        }
        $class = new \ReflectionClass($className);
        $action = $class->getMethod($actionName);
        $reader = new AnnotationReader();

        foreach ($reader->getMethodAnnotations($action) as $annotation) {
            switch (get_class($annotation)) {
                case ResponseFilterAnnotations::class:
                    return $this->responseFilter($annotation, $content, $className);
                default:
                    return $response;
            }
        }

        return $response;
    }

    private function responseFilter($annotation, $content, $className): ResponseInterface
    {
        $class = $annotation->getClass() ?: $this->getFilterClassName($className);

        $fieldFilter = new $class();
        $mode = $annotation->getMode();

        if ($mode) {
            $fieldFilter->setMode($mode);
        }

        $content = $fieldFilter->filter($content);

        $response = Context::get(ResponseInterface::class);
        $response->getBody()->write($content);
        Context::set(ResponseInterface::class, $response);

        return $response->withAddedHeader('Content-Type', 'application/json');
    }

    private function getClassAndAction(ServerRequestInterface $request): array
    {
        $name = $request->getAttribute(Dispatched::class)->handler->callback;
        if (! is_string($name)) {
            return [null, null];
        }

        [$class, $action] = explode('@', $name);

        return [$class, $action];
    }

    private function getFilterClassName($className): string
    {
        $class = substr(substr(strrchr($className, '\\'), 1), 0, -10);
        return preg_replace('/[A-Z|a-z]+Controller$/', 'Filter\\' . $class . 'Filter', $className);
    }
}
