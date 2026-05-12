<?php

declare(strict_types=1);

namespace Vivarium\Test\Container;

use PHPUnit\Framework\TestCase;
use Vivarium\Container\Binding;
use Vivarium\Container\ResolutionChain;
use Vivarium\Container\Scope;
use Vivarium\Container\Solver;
use Vivarium\Test\Assertion\Stub\StubClass;

/** @coversDefaultClass \Vivarium\Container\ResolutionChain */
final class ResolutionChainTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::withNode
     * @covers ::get
     * @covers ::resolve
     * @covers ::buildNext
     */
    public function testGetReturnsInstanceFromSolver(): void
    {
        $instance = new StubClass();
        $solver   = new Solver();

        $solver->bind(StubClass::class)
            ->toInstance($instance)
            ->service();

        $container = (new ResolutionChain())
            ->withNode($solver, 100);

        $result = $container->get(new Binding(StubClass::class));

        static::assertSame($instance, $result);
    }

    /**
     * @covers ::__construct
     * @covers ::withNode
     * @covers ::has
     * @covers ::resolve
     * @covers ::buildNext
     */
    public function testHasReturnsTrueForBoundType(): void
    {
        $solver = new Solver();

        $solver->bind(StubClass::class)
            ->toInstance(new StubClass())
            ->service();

        $container = (new ResolutionChain())
            ->withNode($solver, 100);

        static::assertTrue($container->has(new Binding(StubClass::class)));
    }

    /**
     * @covers ::__construct
     * @covers ::has
     */
    public function testHasReturnsFalseForUnboundType(): void
    {
        $container = new ResolutionChain();

        static::assertFalse($container->has(new Binding('string')));
    }

    /**
     * @covers ::__construct
     * @covers ::withNode
     * @covers ::get
     * @covers ::resolve
     */
    public function testGetCachesDefinitions(): void
    {
        $callCount = 0;
        $solver    = new Solver();

        $solver->bind(StubClass::class)
            ->toProvider(
                new class ($callCount) implements \Vivarium\Container\Provider {
                    public function __construct(private int &$callCount)
                    {
                    }

                    public function provide(\Vivarium\Container\Container $container): mixed
                    {
                        $this->callCount++;

                        return new StubClass();
                    }

                    public function getTarget(): string
                    {
                        return StubClass::class;
                    }

                    public function getCapabilities(): \Vivarium\Collection\Set\Set
                    {
                        return \Vivarium\Collection\Set\HashSet::fromArray([]);
                    }
                }
            )
            ->service();

        $container = (new ResolutionChain())
            ->withNode($solver, 100);

        $binding = new Binding(StubClass::class);
        $container->get($binding);
        $container->get($binding);

        static::assertSame(1, $callCount);
    }
}
