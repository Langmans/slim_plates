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

## composer.json

```json
{
    "require": {
        "slim/plates": "*@dev"
    }
}
```
## then

```bash
composer install
```

or
```bash
composer update -o
```
