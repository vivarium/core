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
use Vivarium\Assertion\Type\IsAssignableToNullable;
use Vivarium\Test\Assertion\Stub\StubClass;

/** @coversDefaultClass \Vivarium\Assertion\Type\IsAssignableToNullable */
final class IsAssignableToNullableTest extends TestCase
{
    /**
     * @covers ::__construct()
     * @covers ::assert()
     * @covers ::__invoke()
     * @covers ::getAssertion()
     * @dataProvider provideSuccess()
     */
    public function testAssert(string $type, string $nullable): void
    {
        static::expectNotToPerformAssertions();

        (new IsAssignableToNullable($nullable))
            ->assert($type);
    }

    /**
     * @covers ::__construct()
     * @covers ::assert()
     * @covers ::__invoke()
     * @covers ::getAssertion()
     * @dataProvider provideFailure()
     */
    public function testAssertException(string $type, string $nullable, string $message): void
    {
        static::expectException(AssertionFailed::class);
        static::expectExceptionMessage($message);

        (new IsAssignableToNullable($nullable))
            ->assert($type);
    }

    /** @covers ::__construct() */
    public function testConstructorException(): void
    {
        static::expectException(AssertionFailed::class);
        static::expectExceptionMessage('Expected string to be a nullable type (starting with ?). Got "string".');

        new IsAssignableToNullable('string');
    }

    /**
     * @covers ::__construct()
     * @covers ::__invoke()
     * @covers ::getAssertion()
     * @dataProvider provideSuccess()
     */
    public function testInvoke(string $type, string $nullable): void
    {
        static::assertTrue(
            (new IsAssignableToNullable($nullable))($type),
        );
    }

    /**
     * @covers ::__construct()
     * @covers ::__invoke()
     * @covers ::getAssertion()
     * @dataProvider provideFailure()
     */
    public function testInvokeFailure(string $type, string $nullable): void
    {
        static::assertFalse(
            (new IsAssignableToNullable($nullable))($type),
        );
    }

    /** @return array<array<string>> */
    public static function provideSuccess(): array
    {
        return [
            ['string', '?string'],
            ['?string', '?string'],
            ['int', '?int'],
            ['?int', '?int'],
            ['float', '?float'],
            ['int', '?float'],
            [stdClass::class, '?' . stdClass::class],
            [StubClass::class, '?' . StubClass::class],
        ];
    }

    /** @return array<array<string>> */
    public static function provideFailure(): array
    {
        return [
            [
                'int',
                '?string',
                'Expected type to be assignable to nullable type "?string". Got "int".',
            ],
            [
                '?int',
                '?string',
                'Expected type to be assignable to nullable type "?string". Got "?int".',
            ],
            [
                'string',
                '?int',
                'Expected type to be assignable to nullable type "?int". Got "string".',
            ],
            [
                stdClass::class,
                '?string',
                'Expected type to be assignable to nullable type "?string". Got "stdClass".',
            ],
        ];
    }
}
