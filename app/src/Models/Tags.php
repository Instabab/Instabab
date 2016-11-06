<?php

namespace App\Models;

class Tags extends \Illuminate\Database\Eloquent\Model {
	protected $table = 'tags';
	protected $primaryKey = 'id';
	public $timestamps = false;

    /**
     * Return the photos associated with the tag
     */
	public function photos() {
		return $this->belongsToMany('\App\Models\Photo', 'tagsphotos', 'id_tag', 'id_photo');
	}
}