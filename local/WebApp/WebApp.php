<?php

namespace Local\WebApp;

use DI\Container;
use Local\Modules\Session\SessionHandler;
use Local\Settings\Settings;
use Pico;
use Psr\Log\LoggerInterface;

class WebApp extends Pico {

    protected Container $container;
    protected Settings $settings;
    protected LoggerInterface $logger;
    protected SessionHandler $sessionHandler;

    public function __construct($rootDir, Container $container)
    {
        $this->container = $container;
        $this->settings = $container->get(Settings::class);
        $this->logger = $container->get(LoggerInterface::class);
        $this->logger->debug('WebApp::__construct()');

        parent::__construct(
            $rootDir,
            $this->settings->pico->configDir,
            $this->settings->pico->pluginsDir,
            $this->settings->pico->themesDir,
            $this->settings->pico->enableLocalPlugins
        );
    }

    public function getContainer(): Container {
        return $this->container;
    }

    public function getSettings(): Settings {
        return $this->settings;
    }

    public function getLogger(): LoggerInterface {
        return $this->logger;
    }

    /**
     * @return SessionHandler
     */
    public function getSessionHandler(): SessionHandler
    {
        return $this->sessionHandler;
    }


}