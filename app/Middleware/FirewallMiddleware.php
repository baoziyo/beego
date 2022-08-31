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
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
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

    protected Biz $biz;

    /**
     * eg: '/^\/product$\??(.*)/'.
     * @var array|array[]
     */
    protected array $whitelist = [
        'POST' => [],
        'GET' => [],
        'PATCH' => [],
        'DELETE' => [],
    ];

    public function __construct(ContainerInterface $container, HttpResponse $response, RequestInterface $request, Biz $biz)
    {
        $this->container = $container;
        $this->response = $response;
        $this->request = $request;
        $this->biz = $biz;
        if (env('APP_ENV') === 'dev') {
            $this->whitelist['POST'] = array_merge($this->whitelist['POST'], ['/^\/test\??(.*)/']);
            $this->whitelist['GET'] = array_merge($this->whitelist['GET'], ['/^\/test\??(.*)/']);
            $this->whitelist['PATCH'] = array_merge($this->whitelist['PATCH'], ['/^\/test\??(.*)/']);
            $this->whitelist['DELETE'] = array_merge($this->whitelist['DELETE'], ['/^\/test\??(.*)/']);
        }
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $this->request->getPathInfo();

        $type = $this->request->getHeaderLine('Token-Type');
        $token = $this->request->getHeaderLine('Token');

        if ($type !== '' && $token !== '') {
            $userId = $this->getTokenService()->validate($type, $token);
            $this->biz->getContext()::set('currentUserId', $userId);
        }

        if (env('APP_ENV') === 'test' || (!preg_match('/^\/admin(.*)/', $path) && $this->checkWhitelists($path))) {
            return $handler->handle($request);
        }

        if (preg_match('/^\/admin(.*)/', $path)) {
            $currentUser = $this->getUserService()->getCurrentUser();
            if ($currentUser->isAdmin !== BaseService::ENABLED) {
                $this->getRoleService()->isPermission($currentUser->role, $path);
            }
        }

        return $handler->handle($request);
    }

    protected function checkWhitelists(string $path): bool
    {
        foreach ($this->whitelist as $method => $whitelist) {
            foreach ($whitelist as $uri) {
                if ($this->request->getMethod() === $method
                    && preg_match($uri, $path)
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
