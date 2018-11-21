<?php 

use \Slim\Http\Request;
use \Slim\Http\Response;

require __DIR__.'/../controllers/github_service.php';

$app->get('/search/{name}', function (Request $request, Response $response, array $args) {
	// Implement the model code here.
	$name = $args['name'];
	$githubService = new GithubService($name);
	$apiResponse = $githubService->searchRepos();
//	$this->logger->addInfo("Search Repor triggered{$args['name']}");
	$response->getBody()->write($apiResponse);
	return $response;
});