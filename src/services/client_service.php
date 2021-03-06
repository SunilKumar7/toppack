<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ClientService {
	/**
	 * @var GuzzleClient.
	 */
	protected $client;

	/**
	 * ClientService constructor.
	 */
	public function __construct() {
		$this->client = new Client();
	}

	/**
	 * @param string $url - request url
	 * @param array $params - query params.
	 * @return array - follows pattern of [errors=> "", data=> ""]
	 */
	public function executeGET(string $url): array {
		$apiResponse = ["errors"=> "", "data"=> ""];
		try {
			$result = $this->client->request('GET', $url);
		} catch (GuzzleException $err) {
			$apiResponse["errors"] = "Server Error, Please try again.";
			return $apiResponse;
		}
		if ($result->getStatusCode() != 200) {
			$apiResponse["errors"] = "Something went wrong, Please try again.";
			return $apiResponse;
		} else {
			$apiResponse["data"] = $result->getBody()->getContents();
		}
		return $apiResponse;
	}


}