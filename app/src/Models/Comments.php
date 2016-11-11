<?php

namespace App\Models;

class Comments extends \Illuminate\Database\Eloquent\Model {
	protected $table = 'comments';
	protected $primaryKey = 'id';
	public $timestamps = false;

    /**
     * Return the photo associated to the comment
     */
	public function photo() {
		return $this->belongsTo('\App\Models\Photo', 'id_photo');
	}
    
    /**
     * Return the user who wrote the comment
     */
    public function user() {
		return $this->belongsTo('\App\Models\User', 'id_user');
	}
}