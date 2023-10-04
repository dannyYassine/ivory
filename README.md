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
```

## Routing:
```php
$app->get('/', HomeController::class);
$app->post('/name', SaveController::class);
$app->put('/name', UpdateController::class);
$app->delete('/delete', DeleteController::class);
```

## Use case driven controllers:
```php
class HomeController {
    public function execute(Request $request): string {
        return true;
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
// third arugment in router definition
$app->get('/name', NameController::class, [NameMiddleware::class]);
```
