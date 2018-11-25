<?php

use \Illuminate\Database\Eloquent\Model;

class Repository extends Model {
	/**
	 * @var string
	 */
	protected $table = 'repositories';
	/**
	 * @var array
	 */
	protected $fillable = [
		"repository_id",
		"full_name",
		"owner_name",
		"repo_name",
		"description",
		"star_count",
		"fork_count",
		"repo_url",
		"imported"
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function packages() {
		return $this->belongsToMany(Package::class, "repository_packages","repository_id", "package_id");
	}
}
