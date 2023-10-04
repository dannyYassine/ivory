<?php

namespace Swoole;

use OpenSwoole\Http\Server;
use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;
use Swoole\Router;
use Throwable;

class Application {
    protected ?string $host = null;
    protected ?int $port = null;

    protected array $diAttributes = [];

    private ?\OpenSwoole\HTTP\Server $server = null;

    private ?\DI\Container $di = null;

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

    public function bind(string $class, callable $callable): self
    {
        $this->diAttributes[$class] = $callable;

        return $this;
    }

    public function get(string $path, string $controller): self {
        $this->router->get(path: $path, controller: $controller);

        return $this;
    }

    public function post(string $path, string $controller): self {
        $this->router->post(path: $path, controller: $controller);

        return $this;
    }

    public function put(string $path, string $controller): self {
        $this->router->put(path: $path, controller: $controller);

        return $this;
    }

    public function delete(string $path, string $controller): self {
        $this->router->delete(path: $path, controller: $controller);

        return $this;
    }

    protected function preStart(): void
    {
        $this->server = new \OpenSwoole\HTTP\Server($this->host, $this->port);

        $this->server->set([
            "worker_num" => 4,
            "backlog" => 128
        ]);
        
        $this->server->on("Start", function(Server $server)
        {
            $host = $this->host;
            $port = $this->port;

            echo "Ivory http server started at: http://$host:$port\n";
        });

        $this->di = new \DI\Container($this->diAttributes);

        $this->server->on("Request", function(Request $request, Response $response)
        {
            $response->header("Content-Type", "application/json");
        
            try {
                $result = $this->router->handle($request, $this->di);
                $response->end(json_encode(['data' => $result]));
            } catch (Throwable $e) {
                $response->end(json_encode(['request' => $request, 'error' => $e->getMessage(), 'trace' => $e->getTrace()]));
            }
        });
    }

    public function start(): void {
        $this->preStart();
        $this->server->start();
    }
}