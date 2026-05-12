<?php

declare(strict_types=1);

namespace Vivarium\Test\Container\Definition;

use PHPUnit\Framework\TestCase;
use Vivarium\Container\Container;
use Vivarium\Container\Definition\Transient;
use Vivarium\Container\Enhancement;
use Vivarium\Container\Provider;
use Vivarium\Test\Assertion\Stub\StubClass;

/** @coversDefaultClass \Vivarium\Container\Definition\Transient */
final class TransientTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::solve
     */
    public function testSolveReturnsProviderResult(): void
    {
        $instance  = new StubClass();
        $provider  = $this->createMock(Provider::class);
        $container = $this->createMock(Container::class);

        $provider->expects(static::once())
            ->method('provide')
            ->with($container)
            ->willReturn($instance);

        $definition = new Transient($provider);

        static::assertSame($instance, $definition->solve($container));
    }

    /**
     * @covers ::__construct
     * @covers ::solve
     */
    public function testSolveCreatesNewInstanceEachTime(): void
    {
        $provider  = $this->createMock(Provider::class);
        $container = $this->createMock(Container::class);

        $provider->expects(static::exactly(2))
            ->method('provide')
            ->with($container)
            ->willReturnCallback(static fn (): StubClass => new StubClass());

        $definition = new Transient($provider);

        $instance1 = $definition->solve($container);
        $instance2 = $definition->solve($container);

        static::assertNotSame($instance1, $instance2);
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

        $definition1 = new Transient($provider);
        $definition2 = $definition1->withEnhancement($enhancement, 100);

        static::assertNotSame($definition1, $definition2);
    }

    /**
     * @covers ::__construct
     * @covers ::withEnhancement
     * @covers ::solve
     */
    public function testSolveAppliesEnhancementsInPriorityOrder(): void
    {
        $instance  = new StubClass();
        $provider  = $this->createMock(Provider::class);
        $container = $this->createMock(Container::class);
        $enhanced1 = new StubClass();
        $enhanced2 = new StubClass();

        $provider->method('provide')
            ->willReturn($instance);

        $enhancement1 = $this->createMock(Enhancement::class);
        $enhancement2 = $this->createMock(Enhancement::class);

        $enhancement1->expects(static::once())
            ->method('enhance')
            ->with($instance, $container)
            ->willReturn($enhanced1);

        $enhancement2->expects(static::once())
            ->method('enhance')
            ->with($enhanced1, $container)
            ->willReturn($enhanced2);

        $definition = (new Transient($provider))
            ->withEnhancement($enhancement2, 200)
            ->withEnhancement($enhancement1, 100);

        static::assertSame($enhanced2, $definition->solve($container));
    }

    /**
     * @covers ::__construct
     * @covers ::solve
     */
    public function testSolveWithoutEnhancementsReturnsProviderResultDirectly(): void
    {
        $instance  = new StubClass();
        $provider  = $this->createMock(Provider::class);
        $container = $this->createMock(Container::class);

        $provider->method('provide')
            ->willReturn($instance);

        $definition = new Transient($provider);

        static::assertSame($instance, $definition->solve($container));
    }
}
