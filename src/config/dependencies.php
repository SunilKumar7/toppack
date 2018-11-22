<?php
// DIC
$container = $app->getContainer();

$container['logger'] = function ($c) {
	$loggerSettings = $c->get('settings')['logger'];
	$logger = new \Monolog\Logger($loggerSettings['name']);
	$fileHandler = new \Monolog\Handler\StreamHandler($loggerSettings['path'], $loggerSettings['level']);
	$logger->pushHandler($fileHandler);
	return $logger;
};