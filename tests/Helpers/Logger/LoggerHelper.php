<?php

namespace Tests\Helpers\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Log\LoggerInterface;

const ROOT_DIR = __DIR__ . '/../../..';
class LoggerHelper
{
    protected static LoggerInterface $logger;

    static public function getLogger(): LoggerInterface
    {
        if(!isset(self::$logger)) {
            self::$logger = (new Logger('Test'))
                ->pushProcessor(new UidProcessor())
                ->pushHandler(new StreamHandler(ROOT_DIR . '/logs/test.log', Logger::DEBUG));
        }
        return self::$logger;
    }
}