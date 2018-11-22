<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

define('SEARCH_URL', 'https://api.github.com/search/repositories');
define('PAGE_LIMIT', 100);

class GithubService {
	/**
	 * Function that fetches the repos from the GithubAPI for a given query string.
	 * @param int $page
	 * @param string $query
	 * @return array
	 */
	public static function searchRepositories(int $page, string $query): array {
		$client = new Client();
		$apiResponse = ["errors"=> "", "data"=> ""];
		try {
			$result = $client->request('GET', SEARCH_URL, [
				"query"=> [
					'q' => $query,
					'page'=> $page,
					'per_page' => PAGE_LIMIT
				]
			]);
		} catch (GuzzleException $err) {
			$apiResponse["errors"] = [$err->getMessage()];
			return $apiResponse;
		}
		if ($result->getStatusCode() != 200) {
			$apiResponse["errors"] = ["Something horrible happened"];
			return $apiResponse;
		} else {
			$apiResponse["data"] = $result->getBody();
		}
		return $apiResponse;
	}
}
