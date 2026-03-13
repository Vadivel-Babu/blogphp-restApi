<?php
ini_set('display_errors', 0);
require 'App/autoload.php';

use App\Core\Router;

$route = new Router();

// users routes
$route->get('/users', 'UserController@index', ['auth']);
$route->get('/users/{id}', 'UserController@show', ['auth']);
$route->post('/users', 'UserController@store', ['auth']);

// posts routes
$route->get('/posts', 'PostController@index', ['auth']);
$route->get('/posts/{id}', 'PostController@show', ['auth']);
$route->post('/posts', 'PostController@store', ['auth']);
$route->put('/posts/{id}', 'PostController@update', ['auth']);
$route->delete('/posts/{id}', 'PostController@delete', ['auth']);

// comments routes
$route->get('/comments', 'CommentController@index', ['auth']);

$route->dispatch();
