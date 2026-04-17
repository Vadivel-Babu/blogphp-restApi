<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
ini_set('display_errors', 1);

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require 'App/autoload.php';
require __DIR__.'/vendor/autoload.php';

use App\Core\Router;

$route = new Router();

// users routes
$route->get('api/users', 'UserController@index', ['auth']);
$route->get('api/users/{id}', 'UserController@show', ['auth']);

// posts routes
$route->get('api/posts', 'PostController@index', ['auth']);
$route->get('api/posts/{id}', 'PostController@show', ['auth']);
$route->post('api/posts', 'PostController@store', ['auth']);
$route->post('api/posts/{id}', 'PostController@update', ['auth']);
$route->delete('api/posts/{id}', 'PostController@delete', ['auth']);

// comments routes
$route->get('api/comments', 'CommentController@index', ['auth']);
$route->post('api/comments', 'CommentController@store', ['auth']);
$route->put('api/comments/{id}', 'CommentController@update', ['auth']);
$route->delete('api/comments/{id}', 'CommentController@delete', ['auth']);

// like routes
$route->get('api/likes', 'LikeController@index', ['auth']);
$route->post('api/likes', 'LikeController@store', ['auth']);

// user routes
$route->post('api/register', 'UserController@register', []);
$route->post('api/login', 'UserController@login', []);
$route->post('api/user/{id}', 'UserController@update', ['auth']);
$route->dispatch();
