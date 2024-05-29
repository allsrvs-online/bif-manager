<?php

namespace Local\Api\Bootstrap\Dependencies;

use DI\ContainerBuilder;
use Local\Modules\Providers\UserProvider\UserProvider;
use Local\Modules\Session\SessionHandler;
use Local\Settings\Settings;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerInterface;

class DependenciesLoader
{
    public static function addDefinitions(ContainerBuilder $container): void
    {
        $container->addDefinitions([
            Settings::class => function () {
                $settingsFilePath = ROOT_DIR . '/config/settings.json';
                $settings = json_decode(file_get_contents($settingsFilePath), true);
                if ($settings === null) {
                    throw new \Exception("Failed to parse settings file: " . $settingsFilePath);
                }
                return new Settings($settings);
            },
            LoggerInterface::class => function (ContainerInterface $c) {
                $settings = $c->get(Settings::class);
                switch (strtolower($settings->logger->level)) {
                    case 'debug':
                        $logLevel = Logger::DEBUG;
                        break;
                    case 'info':
                        $logLevel = Logger::INFO;
                        break;
                    case 'notice':
                        $logLevel = Logger::NOTICE;
                        break;
                    case 'warning':
                        $logLevel = Logger::WARNING;
                        break;
                    case 'error':
                        $logLevel = Logger::ERROR;
                        break;
                    case 'critical':
                        $logLevel = Logger::CRITICAL;
                        break;
                    case 'alert':
                        $logLevel = Logger::ALERT;
                        break;
                    case 'emergency':
                        $logLevel = Logger::EMERGENCY;
                        break;
                    default:
                        throw new InvalidArgumentException('Invalid log level: ' . $settings->logger->level);
                }

                $logger = new Logger($settings->logger->name);
                $logger->pushProcessor(new UidProcessor());
                $logger->pushHandler(new StreamHandler(ROOT_DIR . $settings->logger->path,$logLevel));
                return $logger;
            },
            UserProvider::class => function (ContainerInterface $c) {
                $settings = $c->get(Settings::class);
                return new UserProvider($c->get(LoggerInterface::class), ROOT_DIR . $settings->UserProvider->file);
            },
            SessionHandler::class => function (ContainerInterface $c) {
                return new SessionHandler($c->get(LoggerInterface::class), $c->get(UserProvider::class));
            }
        ]);
    }
}