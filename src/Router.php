<?php declare(strict_types=1);

namespace Ivory;

use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;
use DI\Container;

class Router {

    protected array $map = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
        'PATCH' => []
    ];

    protected array $preGlobalMiddlewares = [];
    protected array $postGlobalMiddlewares = [];

    public function addPreGlobalMiddleware(string $globalMiddleware): self
    {
        $this->preGlobalMiddlewares[] = $globalMiddleware;

        return $this;
    }

    public function addPostGlobalMiddleware(string $globalMiddleware): self
    {
        $this->postGlobalMiddlewares[] = $globalMiddleware;

        return $this;
    }

    public function get(string $path, string $controller, array $middlewares = []): self
    {
        $this->map['GET'][$path] = [$controller, $middlewares];

        return $this;
    }

    public function post(string $path, string $controller, array $middlewares = []): self
    {
        $this->map['POST'][$path] = [$controller, $middlewares];

        return $this;
    }
    
    public function put(string $path, string $controller, array $middlewares = []): self
    {
        $this->map['PUT'][$path] = [$controller, $middlewares];

        return $this;
    }

    public function patch(string $path, string $controller, array $middlewares = []): self
    {
        $this->map['PATCH'][$path] = [$controller, $middlewares];

        return $this;
    }

    public function delete(string $path, string $controller, array $middlewares = []): self
    {
        $this->map['DELETE'][$path] = [$controller, $middlewares];

        return $this;
    }

    function handle(Request $request, Response $response, Container $di) {
        foreach ($this->preGlobalMiddlewares as $globalMiddlewareClass) {
            $globalMiddleware = $di->get($globalMiddlewareClass);
            $next = fn () => true;
            $continue = $globalMiddleware->execute($request, $next);
            if (!$continue) {
                return;
            }
        }

        $uri = $request->server['request_uri'];
        $method = $request->server['request_method'];

        $args = $this->map[$method][$uri];
        $controllerClass = $args[0];
        $middlewares = $args[1];
        
        foreach ($middlewares as $middlewareClass) {
            $middleware = $di->get($middlewareClass);
            $next = fn () => true;
            $continue = $middleware->execute($request, $next);
            if (!$continue) {
                return;
            }
        }

        $controller = $di->get($controllerClass);
    
        $result = $controller->execute($request);

        foreach ($this->postGlobalMiddlewares as $globalMiddlewareClass) {
            $globalMiddleware = $di->get($globalMiddlewareClass);
            $next = fn ($result) => $result;
            $continue = $globalMiddleware->execute($result, $request, $response, $next);
            if (!$continue) {
                return;
            }
            $result = $continue;
        }

        return $result;
    }
}