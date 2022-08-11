<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Wechat\Service\Impl;

use App\Biz\Token\Service\TokenService;
use App\Biz\User\Service\UserBindService;
use App\Biz\Wechat\Client\WechatClient;
use App\Biz\Wechat\Exception\WechatException;
use App\Biz\Wechat\Service\WechatService;
use App\Core\Biz\Service\Impl\BaseServiceImpl;

class WechatServiceImpl extends BaseServiceImpl implements WechatService
{
    public function codeToSession(string $code): string
    {
        $response = $this->getClient()->codeToSession($code);

        $this->getUserBindService()->createOrUpdate($response['openid'], UserBindService::WECHAT_TYPE, [
            'type' => UserBindService::WECHAT_TYPE,
            'fromId' => $response['openid'],
            'fromKey' => $response['session_key'],
        ]);

        return $response['openid'];
    }

    public function sendMessage(int $userId, array $data): void
    {
        $userOpenId = $this->getUserBindService()->getFromIdByUserIdAndType($userId, UserBindService::WECHAT_TYPE);

        if ($userOpenId !== null) {
            $this->getClient()->sendMessage($userOpenId, $data);
        }
    }

    public function getAccessToken(): string
    {
        $token = $this->getTokenService()->getToken(TokenService::WECHAT_ACCESS_TOKEN);
        if ($token !== '') {
            return $token;
        }
        $response = $this->getClient()->getAccessToken();
        $this->getTokenService()->updateToken(TokenService::WECHAT_ACCESS_TOKEN, $response['access_token'], $response['expires_in']);

        return $response['expires_in'];
    }

    public function getAppId(): string
    {
        if (env('WECHAT_APP_ID', '') === '') {
            throw new WechatException(WechatException::CONFIG_NOT_FOUND);
        }

        return env('WECHAT_APP_ID');
    }

    public function getAppSecret(): string
    {
        if (env('WECHAT_APP_SECRET', '') === '') {
            throw new WechatException(WechatException::CONFIG_NOT_FOUND);
        }

        return env('WECHAT_APP_SECRET');
    }

    private function getClient(): WechatClient
    {
        return make(WechatClient::class, [$this->biz]);
    }

    private function getTokenService(): TokenService
    {
        return $this->biz->getService('Token:Token');
    }

    private function getUserBindService(): UserBindService
    {
        return $this->biz->getService('User:UserBind');
    }
}
