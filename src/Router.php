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

    protected ?array $preGlobalMiddlewares = null;
    protected ?array $postGlobalMiddlewares = null;

    public function addPreGlobalMiddleware(string $globalMiddleware): self
    {
        if (!$this->preGlobalMiddlewares) {
            $this->preGlobalMiddlewares = [];
        }
        $this->preGlobalMiddlewares[] = $globalMiddleware;

        return $this;
    }

    public function addPostGlobalMiddleware(string $globalMiddleware): self
    {
        if (!$this->preGlobalMiddlewares) {
            $this->postGlobalMiddlewares = [];
        }
        $this->postGlobalMiddlewares[] = $globalMiddleware;

        return $this;
    }

    public function get(string $path, string $controller, ?array $middlewares = null): self
    {
        $this->map['GET'][$path] = [$controller, $middlewares];

        return $this;
    }

    public function post(string $path, string $controller, ?array $middlewares = null): self
    {
        $this->map['POST'][$path] = [$controller, $middlewares];

        return $this;
    }
    
    public function put(string $path, string $controller, ?array $middlewares = null): self
    {
        $this->map['PUT'][$path] = [$controller, $middlewares];

        return $this;
    }

    public function patch(string $path, string $controller, ?array $middlewares = null): self
    {
        $this->map['PATCH'][$path] = [$controller, $middlewares];

        return $this;
    }

    public function delete(string $path, string $controller, ?array $middlewares = null): self
    {
        $this->map['DELETE'][$path] = [$controller, $middlewares];

        return $this;
    }

    function handle(Request $request, Response $response, Container $di) {
        if ($this->preGlobalMiddlewares) {
            foreach ($this->preGlobalMiddlewares as $globalMiddlewareClass) {
                $globalMiddleware = $di->get($globalMiddlewareClass);
                $next = fn () => true;
                $continue = $globalMiddleware->execute($request, $next);
                if (!$continue) {
                    return;
                }
            }
        }

        $uri = $request->server['request_uri'];
        $method = $request->server['request_method'];

        $args = $this->map[$method][$uri];
        
        if ($middlewares = $args[1]) {
            foreach ($middlewares as $middlewareClass) {
                $middleware = $di->get($middlewareClass);
                $next = fn () => true;
                $continue = $middleware->execute($request, $next);
                if (!$continue) {
                    return;
                }
            }
        }

        $controllerClass = $args[0];
        $controller = $di->get($controllerClass);
    
        $result = $controller->execute($request);

        if ($this->preGlobalMiddlewares) {
            foreach ($this->postGlobalMiddlewares as $globalMiddlewareClass) {
                $globalMiddleware = $di->get($globalMiddlewareClass);
                $next = fn ($result) => $result;
                $continue = $globalMiddleware->execute($result, $request, $response, $next);
                if (!$continue) {
                    return;
                }
                $result = $continue;
            }
        }

        return $result;
    }
}