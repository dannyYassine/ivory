<?php

namespace Dev\Middlewares;

use Dev\Services\ValidateIPService;
use OpenSwoole\Http\Request;
use UnexpectedValueException;

class CheckIPMiddleware {
    function __construct(protected ValidateIPService $validateIPService) {
        //
    }

    public function execute(Request $request, callable $next) {
        if (!$this->validateIPService->validate($request->server['remote_addr'])) {
            throw new UnexpectedValueException('Wrong ip');
        }

        return $next();
    }
}