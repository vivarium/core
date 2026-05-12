<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

declare(strict_types=1);

namespace Vivarium\Test\Assertion\Var;

use PHPUnit\Framework\TestCase;
use Vivarium\Assertion\Exception\AssertionFailed;
use Vivarium\Assertion\Var\IsConstant;

/** @coversDefaultClass \Vivarium\Assertion\Var\IsConstant */
final class IsConstantTest extends TestCase
{
    /**
     * @covers ::assert()
     * @dataProvider provideSuccess()
     */
    public function testAssert(string $var): void
    {
        static::expectNotToPerformAssertions();

        (new IsConstant())
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

        (new IsConstant())
            ->assert($var);
    }

    /**
     * @covers ::__invoke()
     * @dataProvider provideSuccess()
     */
    public function testInvoke(string $var): void
    {
        static::assertTrue(
            (new IsConstant())($var),
        );
    }

    /**
     * @covers ::__invoke()
     * @dataProvider provideFailure()
     */
    public function testInvokeFailure(mixed $var): void
    {
        static::assertFalse(
            (new IsConstant())($var),
        );
    }

    /** @return array<array{0:string}> */
    public static function provideSuccess(): array
    {
        return [
            ['PHP_INT_MAX'],
            ['PHP_EOL'],
            ['PHP_VERSION'],
        ];
    }

    /** @return array<array{0:mixed, 1:string}> */
    public static function provideFailure(): array
    {
        return [
            ['NOT_A_CONSTANT', 'Expected "NOT_A_CONSTANT" to be a defined constant.'],
            [42, 'Expected 42 to be a defined constant.'],
        ];
    }
}
