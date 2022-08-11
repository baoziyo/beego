<?php
/*
 * Sunny 2021/11/24 下午7:25
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Controller\Api\Login;

use App\Annotation\ResponseFilter\ResponseFilter;
use App\Biz\User\Service\TokenService;
use App\Controller\AbstractController;
use App\Controller\Api\Login\Filter\LoginFilter;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class LoginController extends AbstractController
{
    #[ResponseFilter(class: LoginFilter::class)]
    public function post(): PsrResponseInterface
    {
        $params = $this->request->post();
        $token = $this->getTokenService()->generateToken($params);

        return $this->buildRequest($token);
    }

    public function update(): PsrResponseInterface
    {
        $params = $this->request->post();
        $token = $this->getTokenService()->refreshToken($params);

        return $this->buildRequest($token);
    }

    private function getTokenService(): TokenService
    {
        return $this->biz->getService('User:Token');
    }
}
