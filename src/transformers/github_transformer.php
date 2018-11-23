<?php

use \Illuminate\Support\Carbon;

class GithubTransformer {
	public static function transformRepositories(string $items): array {
		$transformedItems = [];
		$now = Carbon::now('utc')->toDateTimeString();
		$itemsJson = json_decode($items, true)['items'];
		foreach ($itemsJson as $repo) {
			array_push($transformedItems, [
				"repo_id" 		=> $repo['id'],
				"full_name" 	=> $repo["full_name"],
				"owner_name" 	=> $repo["owner"]["login"],
				"repo_name"  	=> $repo["name"],
				"description" => $repo["description"],
				"star_count" 	=> $repo["stargazers_count"],
				"fork_count" 	=> $repo["forks_count"],
				"repo_url" 		=> $repo-["html_url"],
				"created_at" 	=> $now,
				"updated_at" 	=> $now,
				"imported" 		=> false,
			]);
		}
		return $transformedItems;
	}

	public static function transformPackages(string $packages): array {
		$processedPackages = [];
		$now = Carbon::now('utc')->toDateTimeString();
		$packagesJson = json_decode($packages, true);
		$mergedPackages = array_merge($packagesJson['devDependencies'], $packagesJson['dependencies']);
		foreach (array_keys($mergedPackages) as $dependency) {
			array_push($processedPackages, [
				"package_name"	=> $dependency,
				"created_at"		=> $now
			]);
		}
		return $processedPackages;
	}
}