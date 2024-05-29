<?php

use Local\Api\Bootstrap\Bootstrap;
use Local\Api\Bootstrap\BootstrapApiLoader;


session_start();
if(!isset($_SESSION['user'])){
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

require '../vendor/autoload.php';
define('ROOT_DIR', realpath(__DIR__ . '/..'));

$container = Bootstrap::getContainer();
$api = BootstrapApiLoader::load($container);
$api->run();