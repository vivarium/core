<?php

declare(strict_types=1);

namespace Vivarium\Test\Container\Provider;

use PHPUnit\Framework\TestCase;
use Vivarium\Assertion\Exception\AssertionFailed;
use Vivarium\Container\Container;
use Vivarium\Container\Provider\Constant;

use const PHP_VERSION;

/** @coversDefaultClass \Vivarium\Container\Provider\Constant */
final class ConstantTest extends TestCase
{
    /** @covers ::__construct */
    public function testConstructWithValidConstant(): void
    {
        static::expectNotToPerformAssertions();

        new Constant('PHP_VERSION');
    }

    /** @covers ::__construct */
    public function testConstructWithInvalidConstantThrows(): void
    {
        static::expectException(AssertionFailed::class);

        new Constant('NONEXISTENT_CONSTANT');
    }

    /** @covers ::provide */
    public function testProvideReturnsConstantValue(): void
    {
        $provider  = new Constant('PHP_VERSION');
        $container = $this->createMock(Container::class);

        static::assertSame(PHP_VERSION, $provider->provide($container));
    }

    /** @covers ::getTarget */
    public function testGetTargetReturnsNormalizedType(): void
    {
        $provider = new Constant('PHP_VERSION');

        static::assertSame('string', $provider->getTarget());
    }

    /** @covers ::getCapabilities */
    public function testGetCapabilitiesReturnsEmptySet(): void
    {
        $provider     = new Constant('PHP_VERSION');
        $capabilities = $provider->getCapabilities();

        static::assertCount(0, $capabilities);
    }
}
