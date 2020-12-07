# slim-route-attribute-provider
Define [Slim](https://www.slimframework.com) routes by PHP8 [attributes]((https://stitcher.io/blog/attributes-in-php-8)).

## Installation
Install via [Composer](https://getcomposer.org):
```bash
$ composer require jerowork/slim-route-attribute-provider
```

## Configuration
Instantiate `RouteAttributeConfigurator` somewhere close to the construction of your Slim application,
e.g. in your front controller (or ideally register in your PSR-11 container).

Basic configuration:
```php
use Jerowork\RouteAttributeProvider\RouteAttributeConfigurator;
use Jerowork\RouteAttributeProvider\Slim\SlimRouteAttributeProvider;
use Slim\Factory\AppFactory;

// Setup a (fictive) PSR-11 container and create Slim application
$container = new Container();
$app       = AppFactory::createFromContainer($container);

// ...

// Setup route attribute configuration
$routeConfigurator = new RouteAttributeConfigurator(
    SlimRouteAttributeProvider::createFromApp($app)
);

$routeConfigurator->configure(sprintf('%s/src/Infrastructure/Api/Http/Action', __DIR__));

// ...

// Run Slim application
$app->run();
```

Extended configuration:

```php
use Jerowork\RouteAttributeProvider\ClassNameLoader\Tokenizer\TokenizerClassNameLoader;
use Jerowork\RouteAttributeProvider\Finder\DirectoryIterator\DirectoryIteratorPhpFileFinder;
use Jerowork\RouteAttributeProvider\RouteAttributeConfigurator;
use Jerowork\RouteAttributeProvider\RouteLoader\Reflection\ReflectionRouteLoader;
use Jerowork\RouteAttributeProvider\Slim\SlimRouteAttributeProvider;

// ...

// All parts of the configurator can be replaced with a custom implementation
$routeConfigurator = new RouteAttributeConfigurator(
    new SlimRouteAttributeProvider(
        $app->getRouteCollector(),
        $container
    ),
    new TokenizerClassNameLoader(
        new DirectoryIteratorPhpFileFinder()
    ),
    new ReflectionRouteLoader()
);

// Multiple directories can be defined
$routeConfigurator->configure(
    sprintf('%s/src/Infrastructure/Api/Http/Action', __DIR__),
    sprintf('%s/src/Other/Controller', __DIR__)
);

// ...
```

## Usage
See [jerowork/route-attribute-provider](http://) for examples.
