<?php

require 'vendor/autoload.php';

use App\Exceptions\Handler;
use App\Models\Database;
use App\Queues\Queue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\Worker;
use Illuminate\Queue\WorkerOptions;

$database = new Database();
$queue = new Queue();

$queue->queue->getContainer()->singleton('db', function () use ($database) {
    return $database->capsule->getDatabaseManager();
});

$worker = new Worker(
    $queue->queue->getQueueManager(),
    new Dispatcher(),
    new Handler($queue->queue->getContainer()),
    function () {return false;}
);

$worker->daemon('default', 'default', new WorkerOptions());

