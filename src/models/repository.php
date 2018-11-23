<?php

use \Illuminate\Database\Eloquent\Model;

class Repository extends Model {
	protected $table = 'repositorys';
	protected $fillable = ['name', 'repo_name'];
}
