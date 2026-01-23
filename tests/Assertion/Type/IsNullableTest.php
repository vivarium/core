<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MIT
 * Copyright (c) 2025 Luca Cantoreggi
 */

declare(strict_types=1);

namespace Vivarium\Test\Assertion\Type;

use PHPUnit\Framework\TestCase;
use stdClass;
use Vivarium\Assertion\Exception\AssertionFailed;
use Vivarium\Assertion\Type\IsNullable;

/** @coversDefaultClass \Vivarium\Assertion\Type\IsNullable */
final class IsNullableTest extends TestCase
{
    /**
     * @covers ::assert()
     * @covers ::__invoke()
     * @dataProvider provideSuccess()
     */
    public function testAssert(string $type): void
    {
        static::expectNotToPerformAssertions();

        (new IsNullable())
            ->assert($type);
    }

    /**
     * @covers ::assert()
     * @covers ::__invoke()
     * @dataProvider provideFailure()
     */
    public function testAssertException(string $type, string $message): void
    {
        static::expectException(AssertionFailed::class);
        static::expectExceptionMessage($message);

        (new IsNullable())
            ->assert($type);
    }

    /**
     * @covers ::assert()
     * @covers ::__invoke()
     * @dataProvider provideInvalid()
     */
    public function testAssertInvalid(int $type, string $message): void
    {
        static::expectException(AssertionFailed::class);
        static::expectExceptionMessage($message);

        (new IsNullable())
            ->assert($type);
    }

    /**
     * @covers ::__invoke()
     * @dataProvider provideSuccess()
     */
    public function testInvoke(string $type): void
    {
        static::assertTrue(
            (new IsNullable())($type),
        );
    }

    /**
     * @covers ::__invoke()
     * @dataProvider provideFailure()
     */
    public function testInvokeFailure(string $type): void
    {
        static::assertFalse(
            (new IsNullable())($type),
        );
    }

    /** @return array<array<string>> */
    public static function provideSuccess(): array
    {
        return [
            ['?string'],
            ['?int'],
            ['?float'],
            ['?bool'],
            ['?array'],
            ['?object'],
            ['?callable'],
            ['?' . stdClass::class],
        ];
    }

    /** @return array<array<string>> */
    public static function provideFailure(): array
    {
        return [
            [
                'string',
                'Expected string to be a nullable type (starting with ?). Got "string".',
            ],
            [
                'int',
                'Expected string to be a nullable type (starting with ?). Got "int".',
            ],
            [
                stdClass::class,
                'Expected string to be a nullable type (starting with ?). Got "stdClass".',
            ],
            [
                'string|int',
                'Expected string to be a nullable type (starting with ?). Got "string|int".',
            ],
        ];
    }

    /** @return array<array{0:int, 1:string}> */
    public static function provideInvalid(): array
    {
        return [
            [42, 'Expected value to be string. Got int.'],
        ];
    }
}