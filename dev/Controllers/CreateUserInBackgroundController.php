<?php

namespace Dev\Controllers;

use Dev\Jobs\CreateUserJob;
use Dev\Queues\Queue;
use OpenSwoole\Http\Request;

class CreateUserInBackgroundController {
    public function __construct(protected Queue $queue)
    {
        
    }
    public function execute(Request $request) {
        return $this->queue->dispatch(CreateUserJob::class);
    }
}