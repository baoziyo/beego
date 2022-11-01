<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\User\Type\UserSource;

use App\Biz\User\Config\BaseUserSource;
use App\Biz\User\Service\UserBindService;
use App\Biz\User\Service\UserService;
use App\Biz\Wechat\Service\WechatService;
use Hyperf\Utils\Str;

class WeChatApp extends BaseUserSource
{
    public function buildRegisterParams(array $params): array
    {
        $params['password'] = Str::random();
        return $params;
    }

    public function handleLogin(array $params): int
    {
        $openId = $this->getWechatService()->codeToSession($params['code']);

        $userBind = $this->getUserBindService()->getByFromIdAndType($openId, UserService::USER_SOURCE_WECHAT_APP);
        if ($userBind === null || $userBind->userId === 0) {
            $user = $this->getUserService()->register(array_merge($params, []));
            $this->getUserBindService()->createOrUpdate([
                'type' => UserService::USER_SOURCE_WECHAT_APP,
                'fromId' => $openId,
            ], [
                'userId' => $user['id'],
            ]);

            return $user->id;
        }

        $this->getUserService()->update($userBind->userId, ['name' => $params['name'], 'avatar' => $params['avatar']]);

        return $userBind->userId;
    }

    private function getWechatService(): WechatService
    {
        return $this->biz->getService('Wechat:Wechat');
    }

    private function getUserBindService(): UserBindService
    {
        return $this->biz->getService('User:UserBind');
    }

    private function getUserService(): UserService
    {
        return $this->biz->getService('User:User');
    }
}
