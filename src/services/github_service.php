<?php

require __DIR__ . '/client_service.php';

define('BASE_URL', 'https://api.github.com');
define('SEARCH_URL', BASE_URL . '/search/repositories');
define('PAGE_LIMIT', 100);

class GithubService {
	/**
	 * Function that fetches the repos from the GithubAPI for a given query string.
	 * @param int $page
	 * @param string $query
	 * @return array
	 */
	public static function searchRepositories(int $page, string $query): array {
		$params = [
			'q' => $query,
			'page'=> $page,
			'per_page' => PAGE_LIMIT
		];
		$client = new ClientService();
		return $client->executeGET(SEARCH_URL, $params);
	}

	/**
	 * Fetches package.json information and fetches the raw data of package.json if exists.
	 * @param string $owner
	 * @param string $repo
	 * @return array
	 */
	public static function importPackages(string $owner, string $repo): array {
		// Hard-coding for now.
		$owner = "chvin";
		$repo = "react-tetris";
		$contentsUrl = BASE_URL . "/repos/{$owner}/{$repo}/contents/package.json";
		$client = new ClientService();
		$response = $client->executeGET($contentsUrl);
		if (!!$response['errors']) {
			$errorMessage = json_decode($response['errors']);
			if (strpos($errorMessage->{'message'}, "Not Found") === false) {
				$response['errors'] = "This project does not contain a package.json file";
			}
			return $response;
		} else {
			$contents = json_decode($response['data']);
			return $client->executeGET($contents->{'download_url'});
		}
	}
}
