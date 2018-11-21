<?php 

define('SEARCH_URL', 'https://api.github.com/search/repositories');

require __DIR__.'/../services/curl.php';

class GithubService {
	protected $query;

	function __construct($query) {
		$this->query = $query;
	}

	public function searchRepos() {
		// Add a curl service which will fetch the data... 
		// As of now, write everything here...
		$curl = new Curl\Curl();
		$curl->get(SEARCH_URL, array(
			'q' => $this->query,
		));
		// TODO: Work on loggers.
			// $this->logger->addInfo($curl->response);
		// $this->logger->addInfo(gettype($curl->response));
		$curl->close();
		//TODO: process the response and return it...
		return $curl->response;
	}
}
