<?php
// DIC
$container = $app->getContainer();

// add logger.
$container['logger'] = function($c) {
	$logger = new \Monolog\Logger('my_logger');
	$file_handler = new \Monolog\Handler\StreamHandler('../../data/log/development.log', Monolog\Logger::INFO);
	$logger->pushHandler($file_handler);
	return $logger;
};