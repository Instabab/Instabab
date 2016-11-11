<?php
/*
	Routes
	controller needs to be registered in dependency.php
*/

//Routes opened to every user
$app->get('/', 'App\Controllers\HomeController:displayHomepage')->setName('homepage');
$app->post('/search', 'App\Controllers\HomeController:search')->setName('search');
$app->get('/post/{id}', 'App\Controllers\PictureController:displayPost')->setName('displayPost');

//Routes restricted to connected users
$app->post('/logout', 'App\Controllers\UserController:logout')->setName('logout')->add('checkAuthentication');
$app->post('/addPicture', 'App\Controllers\PictureController:addPicture')->setName('addPicture')->add('checkAuthentication');
$app->get('/profile/{id}', 'App\Controllers\ProfileController:displayProfile')->setName('displayProfile')->add('checkAuthentication');
$app->post('/profile/settings/{id}', 'App\Controllers\ProfileController:updateProfile')->setName('updateProfile')->add('checkAuthentication');
$app->get('/place/{id}', 'App\Controllers\PlaceController:displayPlace')->setName('displayPlace')->add('checkAuthentication');
$app->get('/places', 'App\Controllers\PlaceController:displayAllPlaces')->setName('displayAllPlaces')->add('checkAuthentication');
$app->get('/tags', 'App\Controllers\TagController:displayLastTags')->setName('displayLastTags')->add('checkAuthentication');
$app->get('/tag/{id}', 'App\Controllers\TagController:displayTag')->setName('displayTag')->add('checkAuthentication');
$app->post('/comment/{id}', 'App\Controllers\PictureController:addComment')->setName('addComment')->add('checkAuthentication');
$app->get('/like/{id}', 'App\Controllers\PictureController:like')->setName('like')->add('checkAuthentication');
$app->get('/dislike/{id}', 'App\Controllers\PictureController:dislike')->setName('dislike')->add('checkAuthentication');

//Routes where the user has to be disconnected
$app->post('/register', 'App\Controllers\UserController:registerUser')->setName('register')->add('checkNoAuthentication');
$app->post('/login', 'App\Controllers\UserController:login')->setName('login')->add('checkNoAuthentication');

//Route redirections to restricted access pages
$app->get('/need/authentication', 'App\Controllers\UserController:userNeedsToAuthenticate')->setName('needAuthentication');
$app->get('/need/logout', 'App\Controllers\UserController:userNeedsToLogout')->setName('needLogout');
