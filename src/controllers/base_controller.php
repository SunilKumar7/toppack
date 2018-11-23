<?php

use \Psr\Container\ContainerInterface;

class BaseController {
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