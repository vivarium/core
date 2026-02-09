<?php

declare(strict_types=1);

namespace Vivarium\Test\Container\Definition;

use PHPUnit\Framework\TestCase;
use Vivarium\Container\Container;
use Vivarium\Container\Definition\Service;
use Vivarium\Container\Enhancement;
use Vivarium\Container\Provider;
use Vivarium\Test\Assertion\Stub\StubClass;

/** @coversDefaultClass \Vivarium\Container\Definition\Service */
final class ServiceTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::solve
     */
    public function testSolveReturnsSameInstanceOnMultipleCalls(): void
    {
        $instance  = new StubClass();
        $provider  = $this->createMock(Provider::class);
        $container = $this->createMock(Container::class);

        $provider->expects(static::once())
            ->method('provide')
            ->with($container)
            ->willReturn($instance);

        $definition = new Service($provider);

        $result1 = $definition->solve($container);
        $result2 = $definition->solve($container);

        static::assertSame($instance, $result1);
        static::assertSame($instance, $result2);
        static::assertSame($result1, $result2);
    }

    /**
     * @covers ::__construct
     * @covers ::solve
     */
    public function testSolveCallsProviderOnlyOnce(): void
    {
        $provider  = $this->createMock(Provider::class);
        $container = $this->createMock(Container::class);

        $provider->expects(static::once())
            ->method('provide')
            ->willReturn(new StubClass());

        $definition = new Service($provider);

        $definition->solve($container);
        $definition->solve($container);
        $definition->solve($container);
    }

    /**
     * @covers ::__construct
     * @covers ::withEnhancement
     * @covers ::solve
     */
    public function testWithEnhancementReturnsNewInstance(): void
    {
        $provider    = $this->createMock(Provider::class);
        $enhancement = $this->createMock(Enhancement::class);

        $definition1 = new Service($provider);
        $definition2 = $definition1->withEnhancement($enhancement, 100);

        static::assertNotSame($definition1, $definition2);
    }

    /**
     * @covers ::__construct
     * @covers ::withEnhancement
     * @covers ::solve
     */
    public function testSolveAppliesEnhancementsAndCachesResult(): void
    {
        $instance  = new StubClass();
        $enhanced  = new StubClass();
        $provider  = $this->createMock(Provider::class);
        $container = $this->createMock(Container::class);

        $provider->expects(static::once())
            ->method('provide')
            ->willReturn($instance);

        $enhancement = $this->createMock(Enhancement::class);
        $enhancement->expects(static::once())
            ->method('enhance')
            ->with($instance, $container)
            ->willReturn($enhanced);

        $definition = (new Service($provider))
            ->withEnhancement($enhancement, 100);

        $result1 = $definition->solve($container);
        $result2 = $definition->solve($container);

        static::assertSame($enhanced, $result1);
        static::assertSame($enhanced, $result2);
    }

    /**
     * @covers ::__construct
     * @covers ::withEnhancement
     */
    public function testWithEnhancementDoesNotAffectOriginalInstance(): void
    {
        $instance  = new StubClass();
        $enhanced  = new StubClass();
        $provider  = $this->createMock(Provider::class);
        $container = $this->createMock(Container::class);

        $provider->method('provide')
            ->willReturn($instance);

        $enhancement = $this->createMock(Enhancement::class);
        $enhancement->method('enhance')
            ->willReturn($enhanced);

        $definition1 = new Service($provider);
        $definition2 = $definition1->withEnhancement($enhancement, 100);

        $result1 = $definition1->solve($container);
        $result2 = $definition2->solve($container);

        static::assertSame($instance, $result1);
        static::assertSame($enhanced, $result2);
    }
}
