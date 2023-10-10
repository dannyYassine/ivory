<?php

namespace App\Queues;

use Illuminate\Queue\Capsule\Manager;
use Illuminate\Queue\QueueManager;

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

        $this->queue->setAsGlobal();
    }

    public function dispatch(string $class): void
    {
        $this->queue->push($class);
    }
}