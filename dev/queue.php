<?php

require 'vendor/autoload.php';

use App\Exceptions\Handler;
use App\Models\Database;
use App\Queues\JobProcessedListener;
use App\Queues\Queue;
use Illuminate\Bus\Dispatcher as BusDispatcher;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Worker;
use Illuminate\Queue\WorkerOptions;

$database = new Database();
$queue = new Queue();

$queue->queue->getContainer()->singleton('db', function () use ($database) {
    return $database->capsule->getDatabaseManager();
});

$dispatcher = $database->getDatabase()->getEventDispatcher();
$dispatcher->listen(JobProcessed::class, JobProcessedListener::class);

$queue->queue->getContainer()->singleton(Database::class, function () use ($database) {
    return $database;
});

$queue->queue->getContainer()->singleton(Illuminate\Contracts\Events\Dispatcher::class, function () use ($dispatcher) {
    return $dispatcher;
});
$queue->queue->getContainer()->singleton(Illuminate\Contracts\Bus\Dispatcher::class, function () use ($queue) {
    return new BusDispatcher($queue->queue->getContainer());
});

$worker = new Worker(
    $queue->queue->getQueueManager(),
    $dispatcher,
    new Handler($queue->queue->getContainer()),
    function () {return false;}
);

$worker->daemon('default', 'default', new WorkerOptions());

