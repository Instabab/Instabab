<?php
/*
	Routes
	controller needs to be registered in dependency.php
*/

$app->get('/', 'App\Controllers\HomeController:displayHomepage')->setName('homepage');
$app->get('/register', 'App\Controllers\UserController:registerUser')->setName('register');