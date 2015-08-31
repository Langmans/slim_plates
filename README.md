Slim/Plates
===========

PHP Slim View for PHP Plates.

- Uses the templates path variable from Slim.
- You can use the same functions as defined in package slim/views.

## Example

```php
<?php
/**
 * @var Composer\Autoload\ClassLoader $autoload
 */
$autoload = require 'vendor/autoload.php';

$app = new Slim\Slim();
$app->view(
    new Slim\Views\Plates(function (League\Plates\Engine $engine) use ($app) {
        $engine->loadExtension(new League\Plates\Extension\URI($app->request()->getPathInfo()));
        $engine->loadExtension(new Slim\Views\PlatesExtension);
    })
);

// routes...

$app->run();

```

## installation

version 1.0 requires plates 3.0. If you need to use 2.x, use 0.2. When installing, use * to determine which version to use.

### via composer.json:
```json
{
    "require": {
        "slim/plates": "*"
    }
}
```

and then

```bash
composer install
```

or
```bash
composer update -o
```

### via command line
```bash
composer require slim/plates
```
