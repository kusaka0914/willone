<?php

namespace App\Services;

use App\Model\HubSpotToken;
use App\Services\HubSpotService;
use Carbon\Carbon;
use Exception;

class HubSpotUserService
{
    protected $hubSpotService;

    public function __construct()
    {
        $this->hubSpotService = new HubSpotService;
    }

    /**
     * ユーザー情報の取得と設定
     *
     * @param string|null $clientId
     * @return array|null
     */
    public function getUserData(?string $clientId): ?array
    {
        if (!ctype_digit($clientId)) {
            return null;
        }

        // HubSpotトークンを取得または更新
        $hubSpotToken = HubSpotToken::first();
        if (Carbon::parse($hubSpotToken->limit_date)->isFuture()) {
            $accessToken = $hubSpotToken->access_token;
        } else {
            $refreshToken = $hubSpotToken->refresh_token;
            $accessToken = $this->hubSpotService->refreshToken($refreshToken);
        }

        // HubSpotユーザー情報の取得
        try {
            $hubspotUser = $this->hubSpotService->getContacts($accessToken, $clientId)['properties'];

            return $hubspotUser;
        } catch (Exception $e) {
            return null;
        }
    }
}
