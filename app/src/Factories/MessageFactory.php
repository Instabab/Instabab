<?php
/**
 * Created by PhpStorm.
 * User: Dylan
 * Date: 10/11/2016
 * Time: 14:07
 */

namespace App\Factories;


class MessageFactory extends BasicFactory
{

    public static function make($message, $success=false, $menuActive='1'){
        $array = parent::make($menuActive);
        $array['message'] = $message;
        $array['success'] = $success;

        return $array;
    }
}