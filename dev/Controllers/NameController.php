<?php

namespace Swoole\Controllers;

use OpenSwoole\Http\Request;

class NameController {
    public function execute(Request $request) {
        return 'Hello world from '.$request->get['name'];
    }
}