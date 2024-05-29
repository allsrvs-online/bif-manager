<?php // @codingStandardsIgnoreFile
/**
 * This file is part of Pico. It's copyrighted by the contributors recorded
 * in the version control history of the file, available from the following
 * original location:
 *
 * <https://github.com/picocms/pico-composer/blob/master/index.php>
 *
 * SPDX-License-Identifier: MIT
 * License-Filename: LICENSE
 */

use Local\Bootstrap\Bootstrap;
use Local\WebApp\WebApp;

// load dependencies
// pico-composer MUST be installed as root package
if (is_file(__DIR__ . '/vendor/autoload.php')) {
    require_once(__DIR__ . '/vendor/autoload.php');
} else {
    die("Cannot find 'vendor/autoload.php'. Run `composer install`.");
}

// Start session
session_start();

const ROOT_DIR = __DIR__;
$container = Bootstrap::getContainer();
// instance Pico
$pico = new WebApp(ROOT_DIR, $container);

// override configuration?
//$pico->setConfig(array());

// run application
echo $pico->run();
