<?php

namespace App\Middlewares;

use Ivory\ValidationFailedException;
use OpenSwoole\Http\Request;
use UnexpectedValueException;

class NameMiddleware {
    public function execute(Request $request, callable $next) {
        if (empty($request->get['name'])) {
            throw new ValidationFailedException('Missing or empty name query param');
        }

        return $next();
    }
}