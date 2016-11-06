<?php

namespace App\Models;

class Notes extends \Illuminate\Database\Eloquent\Model {
	protected $table = 'notes';
	protected $primaryKey = 'id';
	public $timestamps = false;

    /**
     * Return the photo associated to the mark
     */
	public function photo() {
		return $this->belongsTo('\App\Models\Photo', 'id_photo');
	}
    
    /**
     * Return the user who gave a mark
     */
    public function user() {
		return $this->belongsTo('\App\Models\User', 'id_user');
	}
}