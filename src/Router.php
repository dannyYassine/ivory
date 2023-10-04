<?php

namespace Swoole;

use OpenSwoole\Http\Request;
use DI\Container;

class Router {

    protected array $map = [
        'GET' => [],
        'POST' => []
    ];

    public function get(string $path, string $controller): self
    {
        $this->map['GET'][$path] = [$controller];

        return $this;
    }

    public function post(string $path, string $controller): self
    {
        $this->map['POST'][$path] = [$controller];

        return $this;
    }
    
    public function put(string $path, string $controller): self
    {
        $this->map['PUT'][$path] = [$controller];

        return $this;
    }

    public function delete(string $path, string $controller): self
    {
        $this->map['DELETE'][$path] = [$controller];

        return $this;
    }

    function handle(Request $request, Container $di) {
        $uri = $request->server['request_uri'];
        $method = $request->server['request_method'];

        $args = $this->map[$method][$uri];
        $controllerClass = $args[0];
        
        $controller = $di->get($controllerClass);
    
        return $controller->execute($request);
    }
}