<?php

class GithubTransformer {
	public static function transform(string $items): array {
		$transformedItems = [];
		$itemsJson = json_decode($items)->{'items'};
		foreach ($itemsJson as $repo) {
			array_push($transformedItems, [
				"id" => $repo->{'id'},
				"fullName" => $repo->{"full_name"},
				"ownerName" => $repo->{"owner"}->{"login"},
				"repoName" => $repo->{"name"},
				"description" => $repo->{"description"},
				"starCount" => $repo->{"stargazers_count"},
				"forkCount" => $repo->{"forks_count"},
				"repoUrl" => $repo->{"html_url"},
				"imported" => false,
			]);
		}
		return $transformedItems;
	}
}