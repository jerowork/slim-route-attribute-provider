# slim-route-attribute-provider
[![Build Status](https://img.shields.io/endpoint.svg?url=https%3A%2F%2Factions-badge.atrox.dev%2Fjerowork%2Fslim-route-attribute-provider%2Fbadge%3Fref%3Dmain&style=flat-square)](https://github.com/jerowork/slim-route-attribute-provider/actions)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/jerowork/slim-route-attribute-provider.svg?style=flat-square)](https://scrutinizer-ci.com/g/jerowork/slim-route-attribute-provider/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/jerowork/slim-route-attribute-provider.svg?style=flat-square)](https://scrutinizer-ci.com/g/jerowork/slim-route-attribute-provider)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/jerowork/slim-route-attribute-provider.svg?style=flat-square&include_prereleases)](https://packagist.org/packages/jerowork/slim-route-attribute-provider)
[![PHP Version](https://img.shields.io/badge/php-%5E8.0-8892BF.svg?style=flat-square)](http://www.php.net)

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
See [jerowork/route-attribute-provider](https://github.com/jerowork/route-attribute-provider#usage) for examples.
