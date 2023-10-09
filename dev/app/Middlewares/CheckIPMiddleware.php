<?php

namespace App\Middlewares;

use App\Services\ValidateIPService;
use Ivory\ForbiddenException;
use OpenSwoole\Http\Request;

class CheckIPMiddleware {
    function __construct(protected ValidateIPService $validateIPService) {
        //
    }

    public function execute(Request $request, callable $next) {
        if (!$this->validateIPService->validate($request->server['remote_addr'])) {
            throw new ForbiddenException('Wrong ip');
        }

        return $next();
    }
}