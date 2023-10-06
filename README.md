<p align="center">
<img height="auto" style="width: 420px; object-fit: contain;" src="https://github.com/dannyYassine/ivory/blob/main/logo-large.png?raw=true" alt="logo.png">
</p>
<p align="center">
Powered by <a href="https://openswoole.com/" target="_blank">Open Swoole</a>: high-performance http micro PHP library
</p>

```php
$app = new Ivory\Application();

$app->setHost('0.0.0.0')->setPort(8000);

$app->start();

// Ivory http server listening on http://0.0.0.0:8000
```

## Routing:
```php
$app->get('/', HomeController::class);

$app->group('/api', function (Router $router) {
  $router->get('/name', GetController::class);
  $router->post('/name', SaveController::class);
  $router->put('/name', UpdateController::class);
  $router->delete('/name', DeleteController::class);
});
```

## Use case driven controllers:
```php
class HomeController {
  public function execute(Request $request): string {
    return 'This is my response';
  }
}
```

## Bind services/controllers to the D.I. container:
```php
$app->bind(Service::class, function () {
  return new Service();
});

$app->bind(HomeController::class, function (Container $c) {
  return new HomeController(
    service: $c->get(GenerateNameService::class)
  );
});
```

## Dependencies will be injected to the constructor:
```php
class HomeController {
  public function __construct(protected Service $service) {
    //
  }
}
```

## Global middlewares
```php
// before the request is handled
$app->addPreGlobalMiddleware(CheckIPMiddleware::class);

// after the request is handled
$app->addPostGlobalMiddleware(LogRequestMiddleware::class);
```

## Controller middlewares
```php
// third argument in router definition
$app->get('/name', NameController::class, [NameMiddleware::class]);
```
