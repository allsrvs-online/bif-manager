<?php

namespace Local\Api\Bootstrap;

use DI\Container;
use Slim\App;
use Slim\Factory\AppFactory;

class BootstrapApiLoader
{
    public static function load(Container $container): App
    {
        AppFactory::setContainer($container);
        $app = AppFactory::create();
        $app->addRoutingMiddleware();
        $app->addErrorMiddleware(true, true, true);
        return $app;
    }
}