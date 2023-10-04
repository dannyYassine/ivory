<?php

use DI\Container;
use Swoole\Application;
use Swoole\Controllers\DeleteController;
use Swoole\Controllers\HomeController;
use Swoole\Controllers\NameController;
use Swoole\Controllers\SaveController;
use Swoole\Services\GenerateNameService;

require __DIR__ . '/vendor/autoload.php';

$app = new Application();

$app->setHost('0.0.0.0')->setPort(8000);

$app->bind(GenerateNameService::class, function () {
    return new GenerateNameService();
});

$app->bind(HomeController::class, function (Container $c) {
    return new HomeController(generateNameService: $c->get(GenerateNameService::class));
});

$app->get('/', HomeController::class);
$app->get('/name', NameController::class);
$app->post('/name', SaveController::class);
$app->delete('/delete', DeleteController::class);

$app->start();
