<?php

use \Slim\Http\Request;
use \Slim\Http\Response;
use Psr\Container\ContainerInterface;


require_once 'base_controller.php';
require_once __DIR__ . '/../models/repository.php';
require_once __DIR__ . '/../models/package.php';

class PackageController extends BaseController {

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
	public function __construct(ContainerInterface $container)
	{
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
	public function topPackages(Request $request, Response $response): Response {
		$package_repositories = Package::with('repositories', function($query) {
			$query->orderBy('star_count', 'desc');
		})->orderBy('usage_counter', 'desc')->take(10)->get();
		$response->getBody()->write(json_encode($package_repositories));
		return $response;
	}
}