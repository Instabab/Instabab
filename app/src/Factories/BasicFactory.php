<?php


namespace App\Factories;

use App\Models\Photo;
use App\Models\Place;
use Cartalyst\Sentinel\Native\Facades\Sentinel;


class BasicFactory
{

    public static function make($menuActive='1'){
        return $array = array(
                        'menuActive' => $menuActive,
                        'user' => Sentinel::check(),
                        'posts' => Photo::with('notes', 'user', 'place')->get()->sortByDesc('id')->take(15),
                        'places' => Place::all()->sortBy('name')
                );
    }
}