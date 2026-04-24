<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MIT
 * Copyright (c) 2026 Luca Cantoreggi
 */

declare(strict_types=1);

namespace Vivarium\Test\Assertion\Var;

use Closure;
use PHPUnit\Framework\TestCase;
use Vivarium\Assertion\Exception\AssertionFailed;
use Vivarium\Assertion\Var\IsFunction;

/** @coversDefaultClass \Vivarium\Assertion\Var\IsFunction */
final class IsFunctionTest extends TestCase
{
    /**
     * @covers ::assert()
     * @dataProvider provideSuccess()
     */
    public function testAssert(Closure|string $var): void
    {
        static::expectNotToPerformAssertions();

        (new IsFunction())
            ->assert($var);
    }

    /**
     * @covers ::assert()
     * @dataProvider provideFailure()
     */
    public function testAssertException(mixed $var, string $message): void
    {
        static::expectException(AssertionFailed::class);
        static::expectExceptionMessage($message);

        (new IsFunction())
            ->assert($var);
    }

    /**
     * @covers ::__invoke()
     * @dataProvider provideSuccess()
     */
    public function testInvoke(Closure|string $var): void
    {
        static::assertTrue(
            (new IsFunction())($var),
        );
    }

    /**
     * @covers ::__invoke()
     * @dataProvider provideFailure()
     */
    public function testInvokeFailure(mixed $var): void
    {
        static::assertFalse(
            (new IsFunction())($var),
        );
    }

    /** @return array<array{0:Closure|string}> */
    public static function provideSuccess(): array
    {
        return [
            [static fn (): int => 42],
            ['strlen'],
            ['array_map'],
        ];
    }

    /** @return array<array{0:mixed, 1:string}> */
    public static function provideFailure(): array
    {
        return [
            [42, 'Expected value to be a function. Got int.'],
            ['nonExistentFunction', 'Expected value to be a function. Got string.'],
            [['strlen'], 'Expected value to be a function. Got array.'],
        ];
    }
}
