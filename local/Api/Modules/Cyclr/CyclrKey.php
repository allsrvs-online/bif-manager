<?php

namespace Local\Api\Modules\Cyclr;

class CyclrKey
{
    public string $accessKey;
    public int $expires;

    public function __construct($jsonObject = [])
    {
        $this->accessKey = $jsonObject['accessKey'] ?? '';
        if (isset($jsonObject['expires'])) {
            if (is_string($jsonObject['expires'])) {
                $this->expires = $this->stringToTime($jsonObject['expires']);
            } else {
                $this->expires = $jsonObject['expires'];
            }
        } else {
            $this->expires = 0;
        }
    }

    private function stringToTime($string): int
    {
        $result = strtotime($string);
        return $result ? $result : 0;
    }
    public function isExpired(): bool
    {
        return $this->expires < time();
    }
}
