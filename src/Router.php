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

    function __construct(protected string $root = '/') {

    }

    function getRoot(): string {
        return $this->root;
    } 

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
        $this->map['GET'][$this->withRoot($path)] = [$controller, $middlewares];

        return $this;
    }

    public function post(string $path, string $controller, ?array $middlewares = null): self
    {
        $this->map['POST'][$this->withRoot($path)] = [$controller, $middlewares];

        return $this;
    }
    
    public function put(string $path, string $controller, ?array $middlewares = null): self
    {
        $this->map['PUT'][$this->withRoot($path)] = [$controller, $middlewares];

        return $this;
    }

    public function patch(string $path, string $controller, ?array $middlewares = null): self
    {
        $this->map['PATCH'][$this->withRoot($path)] = [$controller, $middlewares];

        return $this;
    }

    public function delete(string $path, string $controller, ?array $middlewares = null): self
    {
        $this->map['DELETE'][$this->withRoot($path)] = [$controller, $middlewares];

        return $this;
    }

    public function group(string $path, callable $callable): self {

        $groupRouter = new Router($this->withRoot($path));

        $callable($groupRouter);

        $this->addRouter($groupRouter);

        return $this;
    }

    protected function withRoot(string $path): string
    {
        if ($path === '/' && $this->root === '/') {
            $path = '';
        }
        if (str_starts_with($path, '/') && $this->root === '/') {
            return $path;
        }

        if (str_starts_with($this->root, '/') && $path === '/') {
            return $this->root;
        }
        
        return $this->root . $path;
    }

    public function addRouter(Router $router): self {
        $this->mergeWith($router);

        return $this;
    }


    public function mergeWith(Router $router): self
    {
        $this->map['GET'] = array_merge($this->map['GET'], $router->map['GET']);
        $this->map['POST'] = array_merge($this->map['POST'], $router->map['POST']);
        $this->map['PUT'] = array_merge($this->map['PUT'], $router->map['PUT']);
        $this->map['PATCH'] = array_merge($this->map['PATCH'], $router->map['PATCH']);
        $this->map['DELETE'] = array_merge($this->map['DELETE'], $router->map['DELETE']);

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

        if (!$args) {
            throw new IvoryRouteNotFoundException('Route not found.');
        }
        
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
    
        $result = $controller->execute($request, $response);

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