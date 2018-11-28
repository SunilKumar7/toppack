<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/settings.php';

$app = new \Slim\App($config);

require_once __DIR__ . "/config/dependencies.php";

// include routes.php file.
require_once __DIR__ . "/config/routes.php";

$app->run();
