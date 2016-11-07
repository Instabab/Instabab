<?php
/*
	Routes
	controller needs to be registered in dependency.php
*/

$app->get('/', 'App\Controllers\HomeController:displayHomepage')->setName('homepage');
$app->post('/register', 'App\Controllers\UserController:registerUser')->setName('register');