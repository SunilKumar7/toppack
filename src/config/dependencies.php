<?php
// DIC

$container = $app->getContainer();

// For logger.
$container['logger'] = function ($c) {
	$loggerSettings = $c->get('settings')['logger'];
	$logger = new \Monolog\Logger($loggerSettings['name']);
	$fileHandler = new \Monolog\Handler\StreamHandler($loggerSettings['path'], $loggerSettings['level']);
	$logger->pushHandler($fileHandler);
	return $logger;
};

// For ORM.
$container['db'] = function ($c) {
	$capsule = new \Illuminate\Database\Capsule\Manager;
	$capsule->addConnection($c->get('settings')['db']);
	$capsule->setAsGlobal();
	$capsule->bootEloquent();
	return $capsule;
};