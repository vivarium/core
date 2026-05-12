<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

declare(strict_types=1);

namespace Vivarium\Test\Assertion\Conditional;

use PHPUnit\Framework\TestCase;
use Vivarium\Assertion\Conditional\IsNotNull;
use Vivarium\Assertion\Exception\AssertionFailed;

/** @coversDefaultClass \Vivarium\Assertion\Conditional\IsNotNull */
final class IsNotNullTest extends TestCase
{
    /**
     * @covers ::assert()
     * @covers ::__invoke()
     * @dataProvider provideSuccess()
     */
    public function testAssert(mixed $value): void
    {
        static::expectNotToPerformAssertions();

        (new IsNotNull())
            ->assert($value);
    }

    /**
     * @covers ::assert()
     * @covers ::__invoke()
     */
    public function testAssertException(): void
    {
        static::expectException(AssertionFailed::class);
        static::expectExceptionMessage('Expected value to be not null.');

        (new IsNotNull())
            ->assert(null);
    }

    /**
     * @covers ::__invoke()
     * @dataProvider provideSuccess()
     */
    public function testInvoke(mixed $value): void
    {
        static::assertTrue(
            (new IsNotNull())($value),
        );
    }

    /** @covers ::__invoke() */
    public function testInvokeFailure(): void
    {
        static::assertFalse(
            (new IsNotNull())(null),
        );
    }

    /** @return array<array{0: mixed}> */
    public static function provideSuccess(): array
    {
        return [
            ['string'],
            [42],
            [0],
            [false],
            [''],
            [[]],
            [0.0],
        ];
    }
}
