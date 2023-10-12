<?php

require 'vendor/autoload.php';

use App\Events\AppEventListener;
use App\Exceptions\Handler;
use App\Models\Database;
use App\Queues\JobProcessedListener;
use App\Queues\Queue;
use Illuminate\Bus\Dispatcher as BusDispatcher;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Worker;
use Illuminate\Queue\WorkerOptions;

$appEventListener = new AppEventListener();
$database = new Database($appEventListener->getDispatcher());
$queue = new Queue();

$queue->queue->getContainer()->singleton('db', function () use ($database) {
    return $database->capsule->getDatabaseManager();
});

$dispatcher = $appEventListener->getDispatcher();
$dispatcher->listen(JobProcessed::class, JobProcessedListener::class);

$queue->queue->getContainer()->bind(Database::class, function () use ($database) {
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

