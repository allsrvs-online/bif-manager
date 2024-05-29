<?php

namespace Local\Api\Modules\Cyclr;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Local\Bootstrap\Settings\BootstrapSettings;
use Local\Module\RestClient\CyclrRestClient;
use Psr\Log\LoggerInterface;

class Cyclr
{
    private const CYCLR_KEY_FILE = './key.json';
    private LoggerInterface $logger;
    private BootstrapSettings $settings;
    private CyclrRestClient $restClient;

    public function __construct(BootstrapSettings $settings, CyclrRestClient $restClient, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->settings = $settings;
        $this->restClient = $restClient;
    }

    public function refreshAccessKey(): CyclrKey
    {
        $cyclrSettings = $this->settings->cyclr;
        $cyclrRequest = new Request('POST', '/oauth/token', [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ], http_build_query([
            'grant_type' => 'client_credentials',
            'client_id' => $cyclrSettings->clusters->eu->api->clientId,
            'client_secret' => $cyclrSettings->clusters->eu->api->clientSecret
        ]));

        try {
            $response = $this->restClient->send($cyclrRequest);
        } catch (GuzzleException $e) {
            $this->logger->error("Cyclr access token refresh failed", ['error' => $e->getMessage()]);
            return new CyclrKey([]);
        }
        if ($response->getStatusCode() !== 200) {
            self::$logger->error("Cyclr access token refresh failed", ['error' => $response->getBody()]);
            return new CyclrKey([]);
        }

        $responseJson = json_decode($response->getBody(), true);
        $cyclrKey = new CyclrKey([
            'accessKey' => $responseJson['access_token'],
            'expires' => $responseJson['.expires'],
        ]);
        $this->saveAccessKey($cyclrKey);
        return $cyclrKey;
    }


    public function getAccessKey(): CyclrKey
    {
        $cyclrKey = new CyclrKey([]);
        if (file_exists(self::CYCLR_KEY_FILE)) {
            $keyFile = fopen(self::CYCLR_KEY_FILE, "r");
            $key = json_decode(fread($keyFile, filesize(self::CYCLR_KEY_FILE)), true);
            $cyclrKey = new CyclrKey($key);
            fclose($keyFile);
        }
        return $cyclrKey;
    }
    public function saveAccessKey(CyclrKey $cyclrKey): void
    {
        $keyFile = fopen(self::CYCLR_KEY_FILE, "w");
        fwrite($keyFile, json_encode($cyclrKey));
        fclose($keyFile);
    }
    public function authenticateRequest(Request $originalRequest): Request
    {
        $cyclrKey = $this->getAccessKey();
        if ($cyclrKey->isExpired()) {
            $cyclrKey = $this->refreshAccessKey();
        }
        $originalRequest = $originalRequest->withHeader('Authorization', 'Bearer ' . $cyclrKey->accessKey);
        return new Request(
            $originalRequest->getMethod(),
            $originalRequest->getUri(),
            $originalRequest->getHeaders(),
            $originalRequest->getBody(),
            $originalRequest->getProtocolVersion()
        );
    }
}
