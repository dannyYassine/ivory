<?php

namespace Dev\Queues;

use Illuminate\Queue\Capsule\Manager;

class Queue {
    public Manager $queue;

    public function __construct()
    {
        $this->queue = new Manager;

        $this->queue->addConnection([
            'driver' => 'database',
            'host' => 'localhost',
            'table' => 'jobs',
            'queue' => 'default',
        ]);

        // Make this Capsule instance available globally via static methods... (optional)
        $this->queue->setAsGlobal();
    }

    public function dispatch(string $class): void
    {
        $this->queue->push($class);
    }
}