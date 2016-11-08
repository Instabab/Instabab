<?php
/*
	Routes
	controller needs to be registered in dependency.php
*/

//Routes opened to every user
$app->get('/', 'App\Controllers\HomeController:displayHomepage')->setName('homepage');

//Routes restricted to conencted users

//Routes where the user has to be disconnected
$app->post('/register', 'App\Controllers\UserController:registerUser')->setName('register')->add('checkNoAuthentication');
$app->post('/login', 'App\Controllers\UserController:login')->setName('login')->add('checkNoAuthentication');

//Route redirections to restricted access pages
$app->get('/need/authentication', 'App\Controllers\UserController:userNeedsToAuthenticate')->setName('needAuthentication');
$app->get('/need/logout', 'App\Controllers\UserController:userNeedsToLogout')->setName('needLogout');