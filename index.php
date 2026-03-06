<?php

require "App/autoload.php";

use App\Core\Router;





$route = new Router();
$route->get('/users', 'UserController@name');
$route->get('/users/{id}', 'UserController@show');
$route->dispatch();