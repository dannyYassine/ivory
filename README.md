<p align="center">
<img height="auto" style="width: 420px; object-fit: contain;" src="https://github.com/dannyYassine/ivory/blob/main/logo-large.png?raw=true" alt="logo.png">
</p>
<p align="center">
Powered by <a href="https://openswoole.com/" target="_blank">Open Swoole</a>: high performant http micro PHP library
</p>

```php
use Ivory\Application;

$app = new Application();

$app->setHost('0.0.0.0')->setPort(8000);

$app->get('/', HomeController::class);

$app->start();
```

## Bind services/controllers to the D.I. container:
```php
$app->bind(GenerateNameService::class, function () {
    return new GenerateNameService();
});

$app->bind(HomeController::class, function (Container $c) {
    return new HomeController(generateNameService: $c->get(GenerateNameService::class));
});
```

## Dependencies will be injected to the constructor:
```php
class HomeController {
    public function __construct(protected GenerateNameService $generateNameService) {
        //
    }

    public function execute(Request $request): string {
        return $this->generateNameService->createName();
    }
}
```

## Use case driven controllers:
```php
class HomeController {
    public function execute(Request $request): string {
        return true;
    }
}
```
