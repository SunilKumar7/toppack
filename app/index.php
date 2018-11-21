<?php


require_once __DIR__.'/../vendor/autoload.php';

$app = new \Slim\App;

require_once __DIR__."/config/dependencies.php";

// include routes.php file.
require_once __DIR__."/config/routes.php";

$app->run();
