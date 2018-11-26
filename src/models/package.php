<?php

use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;


class Package extends Model {
	/**
	 * @var string - table name.
	 */
	protected $table = 'packages';

	protected $primaryKey = "package_id";

	/**
	 * @var array - items to be required for storing.
	 */
	protected $fillable = ['name'];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function repositories() {
		return $this->belongsToMany(Package::class, "repository_packages", "package_id", "repository_id");
	}

	/**
	 * @param array $packageNames Array of package names
	 * @return \Illuminate\Database\Eloquent\Collection returns all imported packages
	 *
	 * @throws \Exception
	 */
	public static function importPackages(array $packageNames) {
		$packageValues = "";
		foreach ($packageNames as $package) {
			$packageValues .= "(?, 1),";
		}
		$packageValues = rtrim($packageValues, ",");
		$query = "INSERT INTO packages (package_name, usage_counter) VALUES {$packageValues} ON DUPLICATE KEY UPDATE usage_counter=usage_counter+1";
		if(!DB::statement($query, $packageNames)) {
			var_dump("ERRORSSS");
		}
		return Package::whereIn("package_name", $packageNames)->get();
	}

}