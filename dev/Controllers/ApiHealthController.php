<?php

namespace Dev\Controllers;

use OpenSwoole\Http\Request;

class ApiHealthController {
    public function execute(Request $request): string {
        return "Alive";
    }
}
