<?php

namespace Swoole\Services;

use OpenSwoole\Http\Request;
use Swoole\Controllers\HomeController;
use Swoole\Controllers\NameController;

class GenerateNameService {
    public function createName(): string
    {
        return 'Welcome home';
    }
}