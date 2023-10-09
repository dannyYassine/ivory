<?php

namespace App\Controllers;

use OpenSwoole\Http\Request;
use App\Services\GenerateNameService;

class HomeController {
    public function __construct(protected GenerateNameService $generateNameService) {
        //
    }

    public function execute(Request $request): string {
        return $this->generateNameService->createName();
    }
}
