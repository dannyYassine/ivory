<?php

use DI\Container;
use Ivory\Application;
use Dev\Controllers\DeleteController;
use Dev\Controllers\HomeController;
use Dev\Controllers\NameController;
use Dev\Controllers\SaveController;
use Dev\Middlewares\CheckIPMiddleware;
use Dev\Middlewares\LogRequestMiddleware;
use Dev\Middlewares\NameMiddleware;
use Dev\Services\GenerateNameService;
use Dev\Services\ValidateIPService;

require 'vendor/autoload.php';

$app = new Application();

$app->setHost('0.0.0.0')->setPort(8000);

$app->addPreGlobalMiddleware(CheckIPMiddleware::class);
$app->addPostGlobalMiddleware(LogRequestMiddleware::class);

$app->bind(GenerateNameService::class, function () {
    return new GenerateNameService();
});

$app->bind(ValidateIPService::class, function () {
    return new ValidateIPService();
});

$app->bind(HomeController::class, function (Container $c) {
    return new HomeController(generateNameService: $c->get(GenerateNameService::class));
});

$app->get('/', HomeController::class);
$app->get('/name', NameController::class, [NameMiddleware::class]);
$app->post('/name', SaveController::class);
$app->delete('/delete', DeleteController::class);

$app->start();
