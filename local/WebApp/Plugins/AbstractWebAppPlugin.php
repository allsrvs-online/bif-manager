<?php

namespace Local\WebApp\Plugins;

use DI\Container;
use Local\Modules\Session\SessionHandler;
use Local\Settings\Settings;
use Local\WebApp\WebApp;
use Psr\Log\LoggerInterface;

class AbstractWebAppPlugin extends \AbstractPicoPlugin
{
    protected Container $container;
    protected Settings $settings;
    protected LoggerInterface $logger;

    public function __construct(WebApp $pico)
    {
        parent::__construct($pico);
        $this->container = $pico->getContainer();
        $this->settings = $this->container->get(Settings::class);
        $this->logger = $this->container->get(LoggerInterface::class);
    }
}