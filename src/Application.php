<?php declare(strict_types=1);

namespace Ivory;

use OpenSwoole\Http\Server;
use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;
use DI\Container;
use Ivory\Router;
use Throwable;

class Application {
    protected ?string $host = null;
    protected ?int $port = null;
    protected int $workerNumbers = 4;
    protected int $backlog = 128;

    protected array $diAttributes = [];

    private ?Server $server = null;

    private ?Container $di = null;

    private Router $router;

    public function __construct() {
        $this->router = new Router();
    }

    public function setHost(string $value): self {
        $this->host = $value;

        return $this;
    }

    public function setPort(int $value): self {
        $this->port = $value;

        return $this;
    }

    public function setWorkerNumbers(int $value): self {
        $this->workerNumbers = $value;

        return $this;
    }

    public function setBacklog(int $value): self {
        $this->backlog = $value;

        return $this;
    }

    public function addPreGlobalMiddleware(string $globalMiddleware): self
    {
        $this->router->addPreGlobalMiddleware($globalMiddleware);

        return $this;
    }
    public function addPostGlobalMiddleware(string $globalMiddleware): self
    {
        $this->router->addPostGlobalMiddleware($globalMiddleware);

        return $this;
    }

    public function bind(string $class, callable $callable): self
    {
        $this->diAttributes[$class] = $callable;

        return $this;
    }

    public function get(string $path, string $controller, ?array $middlewares = null): self {
        $this->router->get(path: $path, controller: $controller, middlewares: $middlewares);

        return $this;
    }

    public function post(string $path, string $controller, ?array $middlewares = null): self {
        $this->router->post(path: $path, controller: $controller, middlewares: $middlewares);

        return $this;
    }

    public function put(string $path, string $controller, ?array $middlewares = null): self {
        $this->router->put(path: $path, controller: $controller, middlewares: $middlewares);

        return $this;
    }

    public function patch(string $path, string $controller, ?array $middlewares = null): self {
        $this->router->patch(path: $path, controller: $controller, middlewares: $middlewares);

        return $this;
    }

    public function delete(string $path, string $controller, ?array $middlewares = null): self {
        $this->router->delete(path: $path, controller: $controller, middlewares: $middlewares);

        return $this;
    }

    public function addRouter(Router $router): self {
        $this->router->addRouter($router);

        return $this;
    }

    public function group(string $path, callable $callable): self {
        $this->router->group($path, $callable);

        return $this;
    }

    protected function bootstrap(): void
    {
        $this->di = new \DI\Container($this->diAttributes);

        $this->server = new \OpenSwoole\HTTP\Server($this->host, $this->port);

        $this->server->set([
            "worker_num" => $this->workerNumbers,
            "backlog" => $this->backlog
        ]);
        
        $this->server->on("Start", function(Server $server)
        {
            $host = $this->host;
            $port = $this->port;

            echo "Ivory http server started at: http://$host:$port\n";
        });

        $this->server->on("Request", function(Request $request, Response $response)
        {
            $response->header("Content-Type", "application/json");
            
            try {
                $result = $this->router->handle($request, $response, $this->di);
                $response->end(json_encode(['status' => 200, 'data' => $result]));
            } catch (IvoryRouteNotFoundException $e) {
                $response->end(json_encode(['status' => 404, 'error' => $e->getMessage(), 'request' => $request, 'trace' => $e->getTrace(), 'application' => $this->router->map]));
            } catch (Throwable $e) {
                $response->end(json_encode(['status' => 500, 'error' => $e->getMessage(), 'request' => $request, 'trace' => $e->getTrace(), 'application' => $this->router]));
            }
        });
    }

    public function start(): void {
        $this->bootstrap();
        $this->server->start();
    }
}