<?php

namespace Local\Bootstrap;

use DI\Container;
use DI\ContainerBuilder;
use Local\Bootstrap\Dependencies\DependenciesLoader;

class Bootstrap
{
    public static function getContainer(): Container
    {
        $builder = new ContainerBuilder();
        if (array_key_exists('environment', $_ENV) && $_ENV['environment'] == 'production') {
            $builder->enableCompilation(__DIR__ . '/../var/cache');
        }
        DependenciesLoader::addDefinitions($builder);
        return $builder->build();
    }
}