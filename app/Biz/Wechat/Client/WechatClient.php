<?php

/*
 * Sunny 2022/8/8 上午11:36
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */
declare(strict_types=1);

namespace App\Biz\Wechat\Client;

use App\Biz\Wechat\Exception\WechatException;
use App\Biz\Wechat\Service\WechatService;
use App\Core\Biz\Container\Biz;
use GuzzleHttp\Client;
use Hyperf\Utils\Codec\Json;

class WechatClient
{
    protected Biz $biz;

    public function __construct(Biz $biz)
    {
        $this->biz = $biz;
    }

    public function codeToSession(string $code, string $appId = null, string $appSecret = null): array
    {
        $response = $this->getClient()->get('/sns/jscode2session', [
            'query' => [
                'appid' => $appId ?? $this->getWechatService()->getAppId(),
                'secret' => $appSecret ?? $this->getWechatService()->getAppSecret(),
                'js_code' => $code, 'grant_type' => 'authorization_code',
            ],
        ]);
        $response = Json::decode($response->getBody()->__toString());
        if (isset($response['errcode']) && $response['errcode'] !== 0) {
            throw new WechatException(WechatException::CLIENT_ERROR, params: [$response['errcode'], $response['errmsg']]);
        }

        return $response;
    }

    public function getAccessToken(string $appId = null, string $appSecret = null): array
    {
        $response = $this->getClient()->get('/cgi-bin/token', [
            'query' => [
                'grant_type' => 'client_credential',
                'appid' => $appId ?? $this->getWechatService()->getAppId(),
                'secret' => $appSecret ?? $this->getWechatService()->getAppSecret(),
            ],
        ]);
        $response = Json::decode($response->getBody()->__toString());
        if (isset($response['errcode']) && $response['errcode'] !== 0) {
            throw new WechatException(WechatException::CLIENT_ERROR, params: [$response['errcode'], $response['errmsg']]);
        }

        return $response;
    }

    public function sendMessage(string $userOpenId, array $data, string $appId = null): array
    {
        $data = array_merge($data, [
            'appid' => $appId ?? $this->getWechatService()->getAppId(),
        ]);

        $response = $this->getClient()->post('/cgi-bin/message/wxopen/template/uniform_send', [
            'query' => [
                'access_token' => $this->getWechatService()->getAccessToken(),
            ],
            'body' => [
                'touser' => $userOpenId,
                'mp_template_msg' => Json::encode($data),
            ],
        ]);
        $response = Json::decode($response->getBody()->__toString());
        if (isset($response['errcode']) && $response['errcode'] !== 0) {
            throw new WechatException(WechatException::CLIENT_ERROR, parans: [$response['errcode'], $response['errmsg']]);
        }

        return $response;
    }

    public function getPhone(string $code): array
    {
        $response = $this->getClient()->post('/wxa/business/getuserphonenumber', [
            'query' => [
                'access_token' => $this->getWechatService()->getAccessToken(),
            ],
            'json' => [
                'code' => $code,
            ],
        ]);
        $response = Json::decode($response->getBody()->__toString());
        if (isset($response['errcode']) && $response['errcode'] !== 0) {
            throw new WechatException(WechatException::CLIENT_ERROR, params: [$response['errcode'], $response['errmsg']]);
        }

        return $response;
    }

    private function getClient(): Client
    {
        return $this->biz->getClient([
            'base_uri' => 'https://api.weixin.qq.com',
        ]);
    }

    private function getWechatService(): WechatService
    {
        return $this->biz->getService('Wechat:Wechat');
    }
}
