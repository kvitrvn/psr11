<?php

declare(strict_types=1);

namespace Kvitrvn\Psr11\Tests;

use Kvitrvn\Psr11\Container;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ContainerTest extends TestCase
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testCanSetAndGetEntry(): void
    {
        $container = new Container();
        $container->set('test', 'value');

        $this->assertTrue($container->has('test'));
        $this->assertEquals('value', $container->get('test'));
    }

    /**
     * @throws ContainerExceptionInterface
     */
    public function testGetThrowsNotFoundException(): void
    {
        $this->expectException(NotFoundExceptionInterface::class);

        $container = new Container();
        $container->get('non_existent_service');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testClosureResolution(): void
    {
        $container = new Container();
        $container->set('closure_service', function () {
            return 'resolved';
        });

        $this->assertEquals('resolved', $container->get('closure_service'));
    }

    /**
     * @throws NotFoundExceptionInterface
     */
    public function testGetThrowsContainerExceptionOnFailure(): void
    {
        $this->expectException(ContainerExceptionInterface::class);

        $container = new Container();
        $container->set('failing_service', function () {
            throw new \Exception('Failed to create service');
        });

        $container->get('failing_service');
    }
}
