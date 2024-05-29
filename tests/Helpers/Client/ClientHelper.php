<?php

namespace Tests\Helpers\Client;

use GuzzleHttp\Client;

class ClientHelper
{
    protected static Client $client;
    public static function getClient(): Client
    {
        if(!isset(self::$client)) {
            self::$client = new Client();
        }
        return self::$client;
    }

}