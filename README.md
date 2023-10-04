# ivory
Powered by Open Swoole: high performant http micro PHP library

```php
use Ivory\Application;

$app = new Application();

$app->setHost('0.0.0.0')->setPort(8000);

$app->get('/', HomeController::class);

$app->start();
```

Bind services/controllers to the D.I. container:
```php
$app->bind(GenerateNameService::class, function () {
    return new GenerateNameService();
});

$app->bind(HomeController::class, function (Container $c) {
    return new HomeController(generateNameService: $c->get(GenerateNameService::class));
});
```