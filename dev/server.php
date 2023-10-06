<?php

use DI\Container;
use Ivory\Application;
use Dev\Controllers\DeleteController;
use Dev\Controllers\GetWeatherController;
use Dev\Controllers\HomeController;
use Dev\Controllers\NameController;
use Dev\Controllers\SaveController;
use Dev\Middlewares\CheckIPMiddleware;
use Dev\Middlewares\LogRequestMiddleware;
use Dev\Middlewares\NameMiddleware;
use Dev\Services\GenerateNameService;
use Dev\Services\ValidateIPService;
use Ivory\Router;

require 'vendor/autoload.php';

$app = new Application();

$app->setHost('0.0.0.0')->setPort($_ENV['PORT'] ?? 8000);

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

$app->group('/api', function (Router $router) {
    $router->get('/', HomeController::class);
    $router->get('/name', NameController::class, [NameMiddleware::class]);
    $router->post('/name', SaveController::class);
    $router->delete('/delete', DeleteController::class);

    $router->group('/weather',function ($router) {
        $router->get('/', GetWeatherController::class);
        $router->get('/city', GetWeatherController::class);
    });
});

$app->start();
