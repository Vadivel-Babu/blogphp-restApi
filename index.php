<?php
header('Access-Control-Allow-Origin: *');
ini_set('display_errors', 1);
require 'App/autoload.php';
require __DIR__.'/vendor/autoload.php';

use App\Core\Router;

$route = new Router();

// users routes
$route->get('/users', 'UserController@index', ['auth']);
$route->get('/users/{id}', 'UserController@show', ['auth']);

// posts routes
$route->get('/posts', 'PostController@index', ['auth']);
$route->get('/posts/{id}', 'PostController@show', ['auth']);
$route->post('/posts', 'PostController@store', ['auth']);
$route->post('/posts/{id}', 'PostController@update', ['auth']);
$route->delete('/posts/{id}', 'PostController@delete', ['auth']);

// comments routes
$route->get('/comments', 'CommentController@index', ['auth']);
$route->post('/comments', 'CommentController@store', ['auth']);
$route->put('/comments/{id}', 'CommentController@update', ['auth']);
$route->delete('/comments/{id}', 'CommentController@delete', ['auth']);

// like routes
$route->get('/likes', 'LikeController@index', ['auth']);
$route->post('/likes', 'LikeController@store', ['auth']);

// user routes
$route->post('/register', 'UserController@register', []);
$route->post('/login', 'UserController@login', []);
$route->post('/user/{id}', 'UserController@update', ['auth']);
$route->dispatch();
