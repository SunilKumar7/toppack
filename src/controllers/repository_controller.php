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
	public function search(Request $request, Response $response, array $args): Response {
		$query = $request->getQueryParam('q');
		$page = $request->getQueryParam('page') || 1;
		$this->logger->debug("Query - {$query} || Page - {$page}");
		$apiResponse = GithubService::searchRepositories($page, $query);
		if (!!$apiResponse['errors']) {
			$response->getBody()->write($apiResponse['errors'][0]);
		} else {
			$processedResult = GithubTransformer::transformRepositories($apiResponse['data']);
			$this->logger->debug(json_encode($processedResult));
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
		$ownerName = "facebook";
		$repoName = "react";
		$this->logger->debug("Owner - {$ownerName} || Repo- {$repoName}");
		$fullName = "$ownerName/$repoName";
		if (Repository::where("full_name", $fullName)->exists()) {
			// Return proper response.
			$this->logger->error("Repository already exists");
		}
		$repositoryResponse = GithubService::searchRepository($ownerName, $repoName);
		$repo = GithubTransformer::transformRepositories($repositoryResponse['data']);
		$repository = new Repository($repo[0]);
		$packagesResponse = GithubService::searchPackages($ownerName, $repoName);
		$packages = GithubTransformer::transformPackages($packagesResponse['data']);
		$this->db->getConnection()->transaction(function () use ($repository, $packages) {
			if (!$repository->save()) {
				$this->logger->error("Unable to save the repository");
			} else {
				// TODO: Handle errors here.
				$importedPackages = Package::importPackages($packages);
				$this->logger->info($importedPackages);
				$repository->packages()->attach($importedPackages);
			}
		});
		$response->getBody()->write(json_encode("Successfully imported"));
		return $response;
	}
}