<?php

use \Psr\Container\ContainerInterface;

class BaseController {
	/**
		* @var \Psr\Container\ContainerInterface
	 */
	protected $container;

	/**
	 * @var \Illuminate\Database\Capsule\Manager
	 */
	protected $db;

	public function __construct(ContainerInterface $container) {
		$this->container = $container;
		$this->db = $container->get("db");
	}
	// Add more methods.
}