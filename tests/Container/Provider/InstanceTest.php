<?php

declare(strict_types=1);

namespace Vivarium\Test\Container\Provider;

use PHPUnit\Framework\TestCase;
use stdClass;
use Vivarium\Container\Capability;
use Vivarium\Container\Container;
use Vivarium\Container\Provider\Instance;
use Vivarium\Test\Assertion\Stub\StubClass;

/** @coversDefaultClass \Vivarium\Container\Provider\Instance */
final class InstanceTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::provide
     */
    public function testProvideReturnsObjectInstance(): void
    {
        $object    = new StubClass();
        $provider  = new Instance($object);
        $container = $this->createMock(Container::class);

        static::assertSame($object, $provider->provide($container));
    }

    /**
     * @covers ::__construct
     * @covers ::provide
     */
    public function testProvideReturnsPrimitiveValue(): void
    {
        $provider  = new Instance(42);
        $container = $this->createMock(Container::class);

        static::assertSame(42, $provider->provide($container));
    }

    /** @covers ::getTarget */
    public function testGetTargetReturnsClassNameForObject(): void
    {
        $provider = new Instance(new StubClass());

        static::assertSame(StubClass::class, $provider->getTarget());
    }

    /** @covers ::getTarget */
    public function testGetTargetReturnsNormalizedTypeForPrimitive(): void
    {
        $provider = new Instance(42);

        static::assertSame('int', $provider->getTarget());
    }

    /** @covers ::getCapabilities */
    public function testGetCapabilitiesForObjectReturnsInterceptableAndDecorable(): void
    {
        $provider     = new Instance(new stdClass());
        $capabilities = $provider->getCapabilities();

        static::assertTrue($capabilities->contains(Capability::INTERCEPTABLE));
        static::assertTrue($capabilities->contains(Capability::DECORABLE));
        static::assertCount(2, $capabilities);
    }

    /** @covers ::getCapabilities */
    public function testGetCapabilitiesForPrimitiveReturnsEmptySet(): void
    {
        $provider     = new Instance('hello');
        $capabilities = $provider->getCapabilities();

        static::assertCount(0, $capabilities);
    }
}
