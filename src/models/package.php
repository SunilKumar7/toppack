<?php

use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;


class Package extends Model {
	/**
	 * @var string - table name.
	 */
	protected $table = 'packages';

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
	public static function importPackages(array $packageNames)
	{
		$query = "INSERT INTO " . (new static)->getTable() . " (package_name, usage_counter) VALUES ";
		$query .= rtrim(str_repeat("(?, 1),", count($packageNames)), ",");
		$query .= " ON DUPLICATE KEY UPDATE usage_counter = usage_counter + 1";
		var_dump($query);
		if (!DB::statement($query, $packageNames))
		{
			throw new \Exception("Failed to import packages in DB");
		}
		return Package::whereIn("package_name", $packageNames)->get();
	}

}