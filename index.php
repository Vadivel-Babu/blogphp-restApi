<?php

require 'App/autoload.php';

use App\Core\Router;

$route = new Router();
$route->get('/users', 'UserController@name', ['auth']);
$route->get('/users/{id}', 'UserController@show', ['auth']);
$route->get('/posts', 'PostController@getAllPosts', ['auth']);
$route->dispatch();
