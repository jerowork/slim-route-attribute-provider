<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\Slim;

use Jerowork\RouteAttributeProvider\Api\Route;
use Jerowork\RouteAttributeProvider\RouteAttributeProviderInterface;
use LogicException;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Interfaces\RouteCollectorInterface;

final class SlimRouteAttributeProvider implements RouteAttributeProviderInterface
{
    private RouteCollectorInterface $routeCollector;
    private ContainerInterface $container;

    public function __construct(RouteCollectorInterface $routeCollector, ContainerInterface $container)
    {
        $this->routeCollector = $routeCollector;
        $this->container      = $container;
    }

    public static function createFromApp(App $app): self
    {
        $container = $app->getContainer();

        if ($container === null) {
            throw new LogicException('A PSR-11 container implementation is required');
        }

        return new self($app->getRouteCollector(), $container);
    }

    public function configure(string $className, string $methodName, Route $route): void
    {
        $routeMap = $this->routeCollector->map(
            $route->getMethods(),
            $route->getPattern(),
            sprintf('%s:%s', $className, $methodName)
        );

        $routeName = $route->getName();
        if ($routeName !== null) {
            $routeMap->setName($routeName);
        }

        foreach ($route->getMiddleware() as $middleware) {
            $routeMap->addMiddleware($this->container->get($middleware));
        }
    }

    public function getRouteCollector(): RouteCollectorInterface
    {
        return $this->routeCollector;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
