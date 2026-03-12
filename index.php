<?php
ini_set('display_errors', 0);
require 'App/autoload.php';

use App\Core\Router;

$route = new Router();
$route->get('/users', 'UserController@index', ['auth']);
$route->get('/users/{id}', 'UserController@show', ['auth']);
$route->get('/posts', 'PostController@index', ['auth']);
$route->post('/users', 'UserController@store', ['auth']);
$route->dispatch();
