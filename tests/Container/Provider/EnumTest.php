<?php

declare(strict_types=1);

namespace Vivarium\Test\Container\Provider;

use PHPUnit\Framework\TestCase;
use Vivarium\Assertion\Exception\AssertionFailed;
use Vivarium\Container\Capability;
use Vivarium\Container\Container;
use Vivarium\Container\Provider\Enum;
use Vivarium\Container\Scope;

/** @coversDefaultClass \Vivarium\Container\Provider\Enum */
final class EnumTest extends TestCase
{
    /** @covers ::__construct */
    public function testConstructWithValidEnum(): void
    {
        static::expectNotToPerformAssertions();

        new Enum(Scope::class, 'SERVICE');
    }

    /** @covers ::__construct */
    public function testConstructWithInvalidEnumThrows(): void
    {
        static::expectException(AssertionFailed::class);

        new Enum('NotAnEnum', 'VALUE');
    }

    /** @covers ::__construct */
    public function testConstructWithInvalidCaseThrows(): void
    {
        static::expectException(AssertionFailed::class);

        new Enum(Scope::class, 'INVALID_CASE');
    }

    /** @covers ::provide */
    public function testProvideReturnsEnumCase(): void
    {
        $provider  = new Enum(Scope::class, 'SERVICE');
        $container = $this->createMock(Container::class);

        static::assertSame(Scope::SERVICE, $provider->provide($container));
    }

    /** @covers ::getTarget */
    public function testGetTargetReturnsEnumClassName(): void
    {
        $provider = new Enum(Scope::class, 'TRANSIENT');

        static::assertSame(Scope::class, $provider->getTarget());
    }

    /** @covers ::getCapabilities */
    public function testGetCapabilitiesReturnsEmptySet(): void
    {
        $provider     = new Enum(Capability::class, 'INJECTABLE');
        $capabilities = $provider->getCapabilities();

        static::assertCount(0, $capabilities);
    }
}
