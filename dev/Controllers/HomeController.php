<?php

namespace Swoole\Controllers;

use OpenSwoole\Http\Request;
use Swoole\Services\GenerateNameService;

class HomeController {
    public function __construct(protected GenerateNameService $generateNameService) {
        //
    }

    public function execute(Request $request): string {
        return $this->generateNameService->createName();
    }
}
