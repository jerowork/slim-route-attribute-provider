<?php

declare(strict_types=1);

namespace Jerowork\RouteAttributeProvider\Slim\Test;

use Jerowork\RouteAttributeProvider\Api\RequestMethod;
use Jerowork\RouteAttributeProvider\Api\Route;
use Jerowork\RouteAttributeProvider\Slim\SlimRouteAttributeProvider;
use Jerowork\RouteAttributeProvider\Slim\Test\Stub\StubMiddlewareA;
use Jerowork\RouteAttributeProvider\Slim\Test\Stub\StubMiddlewareB;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Interfaces\RouteInterface;
use stdClass;

final class SlimRouteAttributeProviderTest extends MockeryTestCase
{
    public function testItShouldCreateFromApp(): void
    {
        $app            = Mockery::mock(App::class);
        $routeCollector = Mockery::mock(RouteCollectorInterface::class);
        $container      = Mockery::mock(ContainerInterface::class);

        $app->allows('getRouteCollector')->andReturn($routeCollector);
        $app->allows('getContainer')->andReturn($container);

        $provider = SlimRouteAttributeProvider::createFromApp($app);

        $this->assertSame($routeCollector, $provider->getRouteCollector());
        $this->assertSame($container, $provider->getContainer());
    }

    public function testItShouldConfigureRoute(): void
    {
        $provider = new SlimRouteAttributeProvider(
            $routeCollector = Mockery::mock(RouteCollectorInterface::class),
            $container = Mockery::mock(ContainerInterface::class),
        );

        $routeCollector->expects('map')
            ->once()
            ->with(
                [RequestMethod::GET],
                '/root',
                stdClass::class . ':__invoke'
            )->andReturn($map = Mockery::mock(RouteInterface::class));

        $map->expects('setName')
            ->once()
            ->with('root.name')
            ->andReturn($map);

        $container->expects('get')
            ->once()
            ->with(StubMiddlewareA::class)
            ->andReturn($middlewareA = new StubMiddlewareA());

        $container->expects('get')
            ->once()
            ->with(StubMiddlewareB::class)
            ->andReturn($middlewareB = new StubMiddlewareB());

        $map->expects('addMiddleware')
            ->ordered()
            ->once()
            ->with($middlewareB);

        $map->expects('addMiddleware')
            ->ordered()
            ->once()
            ->with($middlewareA);

        $provider->configure(
            stdClass::class,
            '__invoke',
            new Route('/root', name: 'root.name', middleware: [StubMiddlewareA::class, StubMiddlewareB::class])
        );
    }
}
