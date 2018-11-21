<?php 

use \Slim\Http\Request;
use \Slim\Http\Response;

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
	// Implement the model code here.
	$name = $args['name'];
	$response->getBody()->write("Hello, $name");

	return $response;
});