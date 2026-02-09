<?php

declare(strict_types=1);

namespace Vivarium\Test\Container\Provider;

use PHPUnit\Framework\TestCase;
use Vivarium\Assertion\Exception\AssertionFailed;
use Vivarium\Container\Capability;
use Vivarium\Container\Container;
use Vivarium\Container\Provider\Fun;
use Vivarium\Test\Assertion\Stub\StubClass;

/** @coversDefaultClass \Vivarium\Container\Provider\Fun */
final class FunTest extends TestCase
{
    /** @covers ::__construct */
    public function testConstructWithValidFunction(): void
    {
        static::expectNotToPerformAssertions();

        new Fun(static fn (Container $c): string => 'test');
    }

    /** @covers ::__construct */
    public function testConstructWithNonCallableThrows(): void
    {
        static::expectException(AssertionFailed::class);

        new Fun('nonexistent_function');
    }

    /** @covers ::provide */
    public function testProvideCallsFunctionWithContainer(): void
    {
        $provider  = new Fun(static fn (Container $c): StubClass => new StubClass());
        $container = $this->createMock(Container::class);

        $result = $provider->provide($container);

        static::assertInstanceOf(StubClass::class, $result);
    }

    /** @covers ::getTarget */
    public function testGetTargetReturnsReturnType(): void
    {
        $provider = new Fun(static fn (Container $c): string => 'hello');

        static::assertSame('string', $provider->getTarget());
    }

    /** @covers ::getTarget */
    public function testGetTargetReturnsMixedForNoReturnType(): void
    {
        $provider = new Fun(static fn (Container $c) => 'hello');

        static::assertSame('mixed', $provider->getTarget());
    }

    /** @covers ::getCapabilities */
    public function testGetCapabilitiesReturnsInterceptableAndDecorable(): void
    {
        $provider     = new Fun(static fn (Container $c): string => 'test');
        $capabilities = $provider->getCapabilities();

        static::assertTrue($capabilities->contains(Capability::INTERCEPTABLE));
        static::assertTrue($capabilities->contains(Capability::DECORABLE));
        static::assertCount(2, $capabilities);
    }
}
