<?php

use \Illuminate\Support\Carbon;

class GithubTransformer {
	public static function transformRepositories(string $items): array {
		$transformedItems = [];
		$itemsJson = json_decode($items, true)['items'];
		foreach ($itemsJson as $repo) {
			array_push($transformedItems, [
				"repository_id" 		=> $repo['id'],
				"full_name" 				=> $repo["full_name"],
				"owner_name" 				=> $repo["owner"]["login"],
				"repo_name"  				=> $repo["name"],
				"description" 			=> $repo["description"],
				"star_count" 				=> $repo["stargazers_count"],
				"fork_count" 				=> $repo["forks_count"],
				"repo_url" 					=> $repo["html_url"],
				"imported" 					=> false,
			]);
		}
		return $transformedItems;
	}

	public static function transformPackages(string $packages): array {
		$packagesJson = json_decode($packages, true);
		$devDependencies = array_keys($packagesJson['devDependencies'] ?? []);
		$dependencies = array_keys($packagesJson['dependencies'] ?? []);
		$mergedPackages = array_unique(array_merge($dependencies, $devDependencies));
		return $mergedPackages;
	}
}