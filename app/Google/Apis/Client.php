<?php

namespace App\Google\Apis;

use Google_Client;
use Google_Service_Drive;
use Google_Service_Sheets;

class Client
{
    private $apiConfig;

    public function __construct()
    {
        $config = new Config();
        $this->apiConfig = $config->api;
    }

    /**
     * Google Clientの取得
     *
     * @return Google_Client
     */
    public function getClient(): Google_Client
    {
        $clientId = $this->apiConfig['CLIENT_ID'];
        $secret = $this->apiConfig['CLIENT_SECRET'];
        $refreshToken = $this->apiConfig['REFRESH_TOKEN'];

        $client = new Google_Client();
        $client->setAccessType('offline');
        $client->setClientId($clientId);
        $client->setClientSecret($secret);
        $client->refreshToken($refreshToken);
        $client->fetchAccessTokenWithRefreshToken($refreshToken);

        // Google Drive と Google Sheets のスコープを追加
        $client->addScope(Google_Service_Sheets::SPREADSHEETS);
        $client->addScope(Google_Service_Drive::DRIVE);
        $client->addScope(Google_Service_Drive::DRIVE_FILE);

        return $client;
    }
}
