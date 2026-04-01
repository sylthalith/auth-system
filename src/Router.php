<?php

namespace App;

class Router
{
    private $routes = [];
    public function addRoute($method, $uri, $handler) {
        $this->routes[$method][$uri] = $handler;
    }
    public function dispatch($method, $uri) {
        $uri = parse_url($uri, PHP_URL_PATH);

        $handler = $this->routes[$method][$uri] ?? null;

        if (!$handler) {
            return http_response_code(404);
        }

        if (is_callable($handler)) {
            return $handler();
        }

        [$class, $method] = $handler;

        return (new $class)->$method();
    }
}