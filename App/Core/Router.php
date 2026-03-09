<?php

namespace App\Core;

// use App\Controllers\UserController;
use App\Helpers\Response;

class Router
{
    private array $routes = [];

    public function get(string $uri, string $action)
    {
        $this->addRoute('GET', $uri, $action);
    }

    private function addRoute(string $method, string $uri, string $action)
    {
        $this->routes[] = compact('method', 'uri', 'action');
    }

    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            $pattern = preg_replace('#\{([^}]+)\}#', '([^/]+)', $route['uri']);
            $pattern = '#^'.$pattern.'$#';

            if (
                $route['method'] === $requestMethod &&
                preg_match($pattern, $requestUri, $matches)
            ) {
                array_shift($matches);  // remove full match

                return $this->callAction($route['action'], $matches);
            }
        }
        Response::json(['message' => 'Route Not Found'], 404);
    }

    private function callAction(string $action, array $params = [])
    {
        [$controller, $method] = explode('@', $action);

        $controllerClass = "App\\Controllers\\$controller";

        if (! class_exists($controllerClass)) {
            throw new \Exception("Controller not found: $controllerClass");
        }

        $controllerInstance = new $controllerClass();

        if (! method_exists($controllerInstance, $method)) {
            throw new \Exception("Method not found: $method");
        }

        return call_user_func_array([$controllerInstance, $method], $params);
    }
}
