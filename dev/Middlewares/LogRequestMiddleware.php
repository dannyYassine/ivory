<?php

namespace Dev\Middlewares;

use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;

class LogRequestMiddleware {
    public function execute(mixed $data, Request $request, Response $response, callable $next) {
        return $next($data);
    }
}