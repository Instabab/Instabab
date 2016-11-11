<?php

namespace App\Models;

class Photo extends \Illuminate\Database\Eloquent\Model {
	protected $table = 'photo';
	protected $primaryKey = 'id';
	public $timestamps = false;

    /**
     * Return the user who put the photo on the website
     */
	public function user() {
		return $this->belongsTo('\App\Models\User', 'id_user');
	}
    
    /**
     * Return the place where the photo has been taken
     */
    public function place() {
        return $this->belongsTo('\App\Models\Place', 'id_place');
    }
    
    /**
     * Return the tags associated to the picture
     */
    public function tags() {
		return $this->belongsToMany('\App\Models\Tags', 'tagsphotos', 'id_photo', 'id_tag');
	}
    
    /**
     * Return the notes of the photo
     */
    public function notes() {
		return $this->hasMany('\App\Models\Notes', 'id_photo');
	}
    
    /**
     * Return the comments of the photo
     */
    public function comments() {
        return $this->hasMany('\App\Models\Comments', 'id_photo');
    }
}