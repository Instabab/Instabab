<?php

namespace App\Models;

class Place extends \Illuminate\Database\Eloquent\Model {
	protected $table = 'place';
	protected $primaryKey = 'id';
	public $timestamps = false;

    /**
     * Return the photos associated with the place
     */
	public function photos() {
		return $this->hasMany('\App\Models\Photo', 'id_place');
	}
}