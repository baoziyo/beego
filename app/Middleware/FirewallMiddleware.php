<?php
/*
 * Sunny 2022/4/20 下午4:09
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Middleware;

use App\Biz\Role\Service\RoleService;
use App\Biz\User\Service\TokenService;
use App\Biz\User\Service\UserService;
use App\Core\Biz\Container\Biz;
use App\Core\Biz\Service\BaseService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\Utils\Codec\Json;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FirewallMiddleware implements MiddlewareInterface
{
    protected ContainerInterface $container;

    protected RequestInterface $request;

    protected HttpResponse $response;

    /**
     * @Inject
     */
    protected Biz $biz;

    // eg: '/^\/product$\??(.*)/'
    protected array $whitelist = [
        'POST' => [],
        'GET' => [],
        'PATCH' => [],
        'DELETE' => [],
    ];

    public function __construct(ContainerInterface $container, HttpResponse $response, RequestInterface $request)
    {
        $this->container = $container;
        $this->response = $response;
        $this->request = $request;
        if (env('APP_ENV') === 'dev') {
            $this->whitelist['POST'] = array_merge($this->whitelist['POST'], ['/^\/test\??(.*)/']);
            $this->whitelist['GET'] = array_merge($this->whitelist['GET'], ['/^\/test\??(.*)/']);
            $this->whitelist['PATCH'] = array_merge($this->whitelist['PATCH'], ['/^\/test\??(.*)/']);
            $this->whitelist['DELETE'] = array_merge($this->whitelist['DELETE'], ['/^\/test\??(.*)/']);
        }
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (! preg_match('/^\/admin(.*)/', parse_url($request->url())['path']) && $this->checkWhitelists($request->url())) {
            return $handler->handle($request);
        }

        $type = $this->request->getHeaderLine('Token-Type');
        $info = $this->getTokenService()->validate($type, $this->request);
        Context::set('user', Json::encode($info));

        $uri = parse_url($request->url())['path'];
        if (! empty($uri) && preg_match('/^\/admin(.*)/', $uri)) {
            $user = $this->getUserService()->getByCache($info['id']);
            if ($user['isAdmin'] !== BaseService::ENABLED) {
                $this->getRoleService()->isPermission($user['role'], $uri);
            }
        }

        return $handler->handle($request);
    }

    protected function checkWhitelists($url): bool
    {
        foreach ($this->whitelist as $method => $whitelist) {
            foreach ($whitelist as $uri) {
                if ($this->request->getMethod() === $method
                    && ! empty(parse_url($url)['path'])
                    && preg_match($uri, parse_url($url)['path'])
                ) {
                    return true;
                }
            }
        }

        return false;
    }

    private function getTokenService(): TokenService
    {
        return $this->biz->getService('User:Token');
    }

    private function getRoleService(): RoleService
    {
        return $this->biz->getService('Role:Role');
    }

    private function getUserService(): UserService
    {
        return $this->biz->getService('User:User');
    }
}
