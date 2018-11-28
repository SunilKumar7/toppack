<?php

use \Slim\Http\Request;
use \Slim\Http\Response;
use Psr\Container\ContainerInterface;

require 'base_controller.php';
require __DIR__ . '/../services/github_service.php';
require __DIR__ . '/../transformers/github_transformer.php';
require __DIR__ . '/../models/repository.php';
require __DIR__ . '/../models/package.php';

class RepositoryController extends BaseController {

	/**
	 * @var \Monolog\Logger
	 */
	protected $logger;

	/**
	 * @var \Illuminate\Database\Capsule\Manager;
	 */
	protected $db;

	/**
	 * RepositoryController constructor.
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container) {
		parent::__construct($container);
		$this->logger = $container->get('logger');
		$this->db = $container->get('db');
	}

	/**
	 * @param Request $request
	 * @param Response $response
	 * @param array $args
	 * @return Response
	 */
	public function search(Request $request, Response $response): Response {
		$query = $request->getQueryParam('q');
		$page = $request->getQueryParam('page') ?? "1";
		$this->logger->debug("Query - {$query} || Page - {$page}");
		$apiResponse = GithubService::searchRepositories($page, $query);
		if (!!$apiResponse['errors']) {
			$response->getBody()->write($apiResponse['errors']);
		} else {
			$processedResult = GithubTransformer::transformRepositories($apiResponse['data'], false);
			$this->logger->debug(json_encode($processedResult));
			$response->getBody()->write(json_encode($processedResult));
		}
		return $response->withHeader('Access-Control-Allow-Origin', '*');
	}

	/**
	 * @param Request $request
	 * @param Response $response
	 * @param array $args
	 * @return Response
	 */
	public function import(Request $request, Response $response, array $args): Response {
		$ownerName = $request->getParsedBodyParam('ownerName');
		$repoName = $request->getParsedBodyParam('repoName');
		$this->logger->debug("Owner - {$ownerName} || Repo- {$repoName}");
		$fullName = "$ownerName/$repoName";
		if (Repository::where("full_name", $fullName)->exists()) {
			// Return proper response.
			$this->logger->error("Repository already exists");
		}
		$repositoryResponse = GithubService::searchRepository($ownerName, $repoName);
		$repo = GithubTransformer::transformRepositories($repositoryResponse['data'], true);
		$repository = new Repository($repo[0]);
		$packagesResponse = GithubService::searchPackages($ownerName, $repoName);
		if (!!$packagesResponse['errors']) {
			$response->getBody()->write($packagesResponse['errors']);
			return $response->withHeader('Access-Control-Allow-Origin', '*')->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
		}
		$packages = GithubTransformer::transformPackages($packagesResponse['data']);
		try {
			$this->db->getConnection()->transaction(function () use ($repository, $packages) {
				$this->logger->debug("Repository :: {$repository}");
				$this->logger->debug("Packages :: {$packages}");
				if (!$repository->save()) {
					$this->logger->error("Unable to save the repository");
				} else {
					// TODO: Handle errors here.
					$importedPackages = Package::importPackages($packages);
					$this->logger->info($importedPackages);
					$repository->packages()->attach($importedPackages);
				}
			});
		} catch (Throwable $e) {
			$this->logger->error("Something went wrong");
		}
		$response->getBody()->write("Successfully imported");
		return $response->withHeader('Access-Control-Allow-Origin', '*')->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
	}
}