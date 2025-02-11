<?php

namespace App\Services;

use App\Model\HubSpotToken;
use Illuminate\Support\Facades\Http;
use Socialite;

class HubSpotService
{
    /**
     * hubpostの認証画面にリダイレクト
     */
    public function redirectToProvider(): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        return Socialite::driver('hubspot')
        ->scopes(config('services.hubspot.scopes'))
        ->redirect();
    }

    /**
     * hubspotのアクセストークンを取得
     * @param string $authorizationCode
     * @throws \Exception
     */
    public function getToken(string $authorizationCode): void
    {
        try {
            $tokenResponse = Http::asForm()->post(config('services.hubspot.get_token_url'), [
                'grant_type'    => 'authorization_code',
                'client_id'     => config('services.hubspot.client_id'),
                'client_secret' => config('services.hubspot.client_secret'),
                'redirect_uri'  => config('services.hubspot.redirect'),
                'code'          => $authorizationCode
            ]);

            if (!$tokenResponse->successful()) {
                $errorData = $tokenResponse->json();
                throw new \Exception($errorData['status']. ':'. $errorData['message']);
            }

            $this->updateToken($tokenResponse);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * リフレッシュトークンを使ってアクセストークンを更新
     * @param string $refreshToken
     * @return string アクセストークン
     */
    public function refreshToken(string $refreshToken): string
    {
        try {
            $tokenResponse = Http::asForm()->post(config('services.hubspot.get_token_url'), [
                'grant_type'    => 'refresh_token',
                'client_id'     => config('services.hubspot.client_id'),
                'client_secret' => config('services.hubspot.client_secret'),
                'redirect_uri'  => config('services.hubspot.redirect'),
                'refresh_token' => $refreshToken
            ]);

            if (!$tokenResponse->successful()) {
                $errorData = $tokenResponse->json();
                throw new \Exception($errorData['status']. ':'. $errorData['message']);
            }

            $this->updateToken($tokenResponse);

            return $tokenResponse->json()['access_token'];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * hubspot_tokenテーブル更
     * @param \Illuminate\Http\Client\Response $tokenResponse
     * @throws \Exception
     */
    public function updateToken(\Illuminate\Http\Client\Response $tokenResponse): void
    {
        \DB::beginTransaction();
        try {
            $dbResult = (new HubSpotToken())->updateToken($tokenResponse->json(), null);
            if ($dbResult) {
                \DB::commit();
            } else {
                throw new \Exception('トークンの更新に失敗しました');
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    /**
     * ユーザー情報を取得
     * @param string $accessToken
     * @param int $userId
     * @return array
     */
    public function getContacts(string $accessToken, int $userId): array
    {
        $properties = [
            'graduation_year',
            'zip',
            'mobilephone',
            'shikaku',
            'state',
            'state2',
            'school2',
            'email',
            'lastname',
            'firstname',
            'furigana',
            'date_of_birth',
            'last_name2',
            'first_name2',
            'phone',
            'birthdate',
        ];
        $url = config('services.hubspot.get_contact_url'). '/'. $userId. '/?properties=' . implode(',', $properties);
        $response = Http::withToken($accessToken)->get($url);

        try {
            if ($response->successful()) {
                return $response->json();
            } else {
                $errorData = $response->json();
                throw new \Exception($errorData['status']. ':'. $errorData['message']);
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
