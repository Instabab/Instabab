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
     * Return the user's marks
     */
    public function notes() {
        return $this->hasMany('\App\Models\Notes', 'id_user');
    }
    
    /**
     * Return the user's comments
     */
    public function comments() {
        return $this->hasMany('\App\Models\Comments', 'id_user');
    }
}