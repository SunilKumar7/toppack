<?php

require __DIR__ . '/client_service.php';

define('BASE_URL', 'https://api.github.com');
define('SEARCH_URL', BASE_URL . '/search/repositories');
define('PACKAGE_NOT_PRESENT', 'This project does not contain a package.json file');
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

	public static function searchRepository(string $ownerName, string $repoName): array {
		$params = [
			"q" => "{$repoName}+user:{$ownerName}"
		];
		$client = new ClientService();
		$response = $client->executeGET(SEARCH_URL, $params);
		return $response;
	}

	/**
	 * Fetches package.json information and fetches the raw data of package.json if exists.
	 * @param string $owner
	 * @param string $repo
	 * @return array
	 */
	public static function searchPackages(string $owner, string $repo): array {
		$client = new ClientService();
		$contentsUrl = BASE_URL . "/repos/{$owner}/{$repo}/contents/package.json";
		$response = $client->executeGET($contentsUrl);
		if (!!$response['errors']) {
			$errorMessage = json_decode($response['errors']);
			if (strpos($errorMessage->{'message'}, "Not Found") === false) {
				$response['errors'] = PACKAGE_NOT_PRESENT;
			}
		} else {
			$contents = json_decode($response['data']);
			$response['data'] = base64_decode($contents->{'content'});
		}
		return $response;
	}
}
