<?php

namespace App\Models;

class User extends \Illuminate\Database\Eloquent\Model {
	protected $table = 'users';
	protected $primaryKey = 'id';
	
    /**
     * Return the user's photos
     */
	public function photos() {
		return $this->hasMany('\App\Models\Photo', 'id_user');
	}
    
    /**
     * Return the user's mark
     */
    public function notes() {
        return $this->belongsTo('\App\Models\Notes', 'id_user');
    }
}