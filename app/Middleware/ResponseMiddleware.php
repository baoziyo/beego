<?php
/*
 * Sunny 2021/11/24 下午5:38
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Middleware;

use App\Annotation\ResponseFilter\ResponseFilter;
use App\Core\Biz\Container\Biz;
use Hyperf\Di\Annotation\AnnotationReader;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\HttpServer\Router\Dispatched;
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

    protected Biz $biz;

    public function __construct(ContainerInterface $container, HttpResponse $response, RequestInterface $request, Biz $biz)
    {
        $this->container = $container;
        $this->response = $response;
        $this->request = $request;
        $this->biz = $biz;
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

    private function annotationReader(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $content = $response->getBody()->getContents();
        [$className, $methodName] = $this->getClassAndMethod($request);
        if (!$className || !$methodName) {
            return $response;
        }
        $class = new \ReflectionClass($className);
        $action = $class->getMethod($methodName);
        $annotationMethodResponseFilter = (new AnnotationReader())->getMethodAnnotation($action, ResponseFilter::class);

        if ($annotationMethodResponseFilter === null) {
            return $response;
        }

        return match (get_class($annotationMethodResponseFilter)) {
            ResponseFilter::class => $this->responseFilter($annotationMethodResponseFilter, $content, $className),
            default => $response,
        };
    }

    private function responseFilter(mixed $annotation, string $content, string $className): ResponseInterface
    {
        $class = $annotation->class;
        $mode = $annotation->mode;
        $fieldFilter = new $class($this->biz);

        if ($mode) {
            $fieldFilter->setMode($mode);
        }

        $content = $fieldFilter->filter($content);

        $response = $this->biz->getContext()::get(ResponseInterface::class);
        $response->getBody()->write($content);
        $this->biz->getContext()::set(ResponseInterface::class, $response);

        return $response->withAddedHeader('Content-Type', 'application/json');
    }

    private function getClassAndMethod(ServerRequestInterface $request): array
    {
        $name = $request->getAttribute(Dispatched::class)->handler->callback;
        if (!is_string($name)) {
            return [null, null];
        }

        [$class, $method] = explode('@', $name);

        return [$class, $method];
    }
}
