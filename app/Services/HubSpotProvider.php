<?php

namespace App\Services;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;
use Laravel\Socialite\Two\ProviderInterface;

class HubSpotProvider extends AbstractProvider implements ProviderInterface
{
    protected $scopeSeparator = ' '; // スコープの区切り文字をスペースに変更

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(config('services.hubspot.oauth_url'), $state);
    }

    /**
     * トークンのURLを取得するメソッド（使用しないため、空の実装）
     */
    protected function getTokenUrl()
    {
        return '';
    }

    /**
     * トークンによってユーザー情報を取得するメソッド（使用しないため、空の実装）
     */
    protected function getUserByToken($token)
    {
        return [];
    }

    /**
     * 取得したユーザー情報をSocialiteのUserオブジェクトにマッピングするメソッド（使用しないため、空の実装）
     */
    protected function mapUserToObject(array $user)
    {
        return new User();
    }
}
