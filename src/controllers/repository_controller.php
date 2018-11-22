<?php

use \Slim\Http\Request;
use \Slim\Http\Response;
use Psr\Container\ContainerInterface;

require 'base_controller.php';
require __DIR__ . '/../services/github_service.php';
require __DIR__ . '/../transformers/github_transformer.php';

class RepositoryController extends BaseController {

	/**
	 * @var \Monolog\Logger
	 */
	protected $logger;

	/**
	 * RepositoryController constructor.
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container) {
		parent::__construct($container);
		$this->logger = $container->get('logger');
	}

	/**
	 * @param Request $request
	 * @param Response $response
	 * @param array $args
	 * @return Response
	 */
	public function search(Request $request, Response $response, array $args): Response {
		$query = $request->getQueryParam('q');
		$page = $request->getQueryParam('page') || 1;
		$this->logger->debug("Query - {$query} || Page - {$page}");
		$apiResponse = GithubService::searchRepositories($page, $query);
		if (!!$apiResponse['errors']) {
			$response->getBody()->write($apiResponse['errors'][0]);
		} else {
			// Add transformation logic here.
			// store data in db and return that processed data..
			$processedResult = GithubTransformer::transform($apiResponse['data']);
			$response->getBody()->write(json_encode($processedResult));
		}
		return $response;
	}

	/**
	 * @param Request $request
	 * @param Response $response
	 * @param array $args
	 * @return Response
	 */
	public function import(Request $request, Response $response, array $args): Response {
		$ownerName = $request->getQueryParam('ownerName') || "";
		$repoName = $request->getQueryParam('repositoryName') || "";
		$this->logger->debug("Owner - {$ownerName} || Repo- {$repoName}");
		$apiResponse = GithubService::importPackages($ownerName, $repoName);
		if (!!$apiResponse['errors']) {
			$response->getBody()->write($apiResponse['errors']);
		} else {
			// Add transformation logic here.
			// store data in db and return that processed data..
			$response->getBody()->write($apiResponse['data']);
		}
		return $response;
	}
}