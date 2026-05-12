<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Test\Type;

use PHPUnit\Framework\TestCase;
use stdClass;
use Vivarium\Test\Assertion\Stub\StubClass;
use Vivarium\Type\Exception\NotAType;
use Vivarium\Type\Type;

/** @coversDefaultClass \Vivarium\Type\Type */
final class TypeTest extends TestCase
{
    /**
     * @covers ::toLiteral()
     * @dataProvider provideLiterals()
     */
    public function testToLiteral(mixed $value, string $literal): void
    {
        static::assertSame($literal, Type::toLiteral($value));
    }

    /**
     * @covers ::toString()
     * @dataProvider provideStrings()
     */
    public function testToString(mixed $value, string $string): void
    {
        static::assertSame($string, Type::toString($value));
    }

    /** @covers ::canonical() */
    public function testCanonical(): void
    {
        $canonical = Type::canonical();

        static::assertContains(Type::INT, $canonical);
        static::assertContains(Type::FLOAT, $canonical);
        static::assertContains(Type::STRING, $canonical);
        static::assertContains(Type::BOOL, $canonical);
        static::assertContains(Type::ARRAY, $canonical);
        static::assertContains(Type::OBJECT, $canonical);
        static::assertContains(Type::CALLABLE, $canonical);
        static::assertContains(Type::MIXED, $canonical);

        static::assertNotContains('integer', $canonical);
        static::assertNotContains('double', $canonical);
        static::assertNotContains('boolean', $canonical);
    }

    /** @covers ::expanded() */
    public function testExpanded(): void
    {
        $expanded = Type::expanded();

        static::assertContains(Type::INT, $expanded);
        static::assertContains(Type::FLOAT, $expanded);
        static::assertContains(Type::STRING, $expanded);
        static::assertContains(Type::BOOL, $expanded);
        static::assertContains(Type::MIXED, $expanded);

        static::assertContains('integer', $expanded);
        static::assertContains('double', $expanded);
        static::assertContains('boolean', $expanded);
    }

    /**
     * @covers ::normalize()
     * @dataProvider provideNormalize()
     */
    public function testNormalize(string $type, string $expected): void
    {
        static::assertSame($expected, Type::normalize($type));
    }

    /**
     * @covers ::normalize()
     * @dataProvider provideNormalizeException()
     */
    public function testNormalizeException(string $type, string $message): void
    {
        static::expectException(NotAType::class);
        static::expectExceptionMessage($message);

        Type::normalize($type);
    }

    /** @return array<array{0:string, 1:string}> */
    public static function provideNormalize(): array
    {
        return [
            ['integer', Type::INT],
            ['double', Type::FLOAT],
            ['boolean', Type::BOOL],
            ['NULL', Type::NULL],
            ['int', Type::INT],
            ['float', Type::FLOAT],
            ['string', Type::STRING],
        ];
    }

    /** @return array<array{0:string, 1:string}> */
    public static function provideNormalizeException(): array
    {
        return [
            [stdClass::class, 'Expected a valid type. Got "stdClass".'],
            ['NotAType', 'Expected a valid type. Got "NotAType".'],
        ];
    }

    /** @return array<array{0:mixed, 1:string}> */
    public static function provideLiterals(): array
    {
        return [
            [true, 'true'],
            [false, 'false'],
            [null, 'null'],
            [[], 'array'],
            ['Hello World', '"Hello World"'],
            [new StubClass(), '"' . StubClass::class . '"'],
            [42, '42'],
            [static fn (mixed $a): mixed => $a, 'callable'],
        ];
    }

    /** @return array<array{0:mixed, 1:string}> */
    public static function provideStrings(): array
    {
        return [
            [true, 'bool'],
            [false, 'bool'],
            [42, 'int'],
            [0.99, 'float'],
            [[], 'array'],
            [null, 'null'],
            [new StubClass(), 'object'],
            [static fn (mixed $a): mixed => $a, 'callable'],
        ];
    }
}
