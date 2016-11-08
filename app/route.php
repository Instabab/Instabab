<?php
/*
	Routes
	controller needs to be registered in dependency.php
*/

$app->get('/', 'App\Controllers\HomeController:displayHomepage')->setName('homepage');
$app->get('/need/authentication', 'App\Controllers\UserController:userNeedsToAuthenticate')->setName('needAuthentication');
$app->get('/need/logout', 'App\Controllers\UserController:userNeedsToLogout')->setName('needLogout');
$app->post('/register', 'App\Controllers\UserController:registerUser')->setName('register');
$app->post('/login', 'App\Controllers\UserController:login')->setName('login');