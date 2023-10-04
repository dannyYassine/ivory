<?php

namespace Dev\Middlewares;

use OpenSwoole\Http\Request;
use UnexpectedValueException;

class NameMiddleware {
    public function execute(Request $request, callable $next) {
        if (empty($request->get['name'])) {
            throw new UnexpectedValueException('Missing or empty name query param');
        }

        return $next();
    }
}