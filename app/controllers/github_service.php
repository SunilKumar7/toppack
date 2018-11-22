<?php 

define('SEARCH_URL', 'https://api.github.com/search/repositories');

//require __DIR__.'/../services/curl.php'; <- Add this later.

class GithubService {
	protected $query; // Holds the query string.
	protected $logger; // Holds the Monolog object.

	/**
		* Constructor of GithubService.		
	 */
	function __construct(Monolog\Logger $logger,string $query) {
		$this->logger = $logger;
		$this->query = $query;
	}

	/**
		* Function that fetches the repos from the GithubAPI for a given query string.
	 */
	public function searchRepos() {
		// Add a curl service which will fetch the data... 
		// As of now, write everything here...
		$curl = new Curl\Curl();
		$curl->get(SEARCH_URL, array(
			'q' => $this->query,
		));
		$this->logger->info($curl->response);
		$this->logger->info(gettype($curl->response));
		$curl->close();
		//TODO: process the response and return it...
		return $curl->response;
	}
}
