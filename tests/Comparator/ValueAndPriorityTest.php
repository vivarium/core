<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) 2026 Luca Cantoreggi
 */

declare(strict_types=1);

namespace Vivarium\Test\Comparator;

use PHPUnit\Framework\TestCase;
use stdClass;
use Vivarium\Comparator\Priority;
use Vivarium\Comparator\ValueAndPriority;

/** @coversDefaultClass \Vivarium\Comparator\ValueAndPriority */
class ValueAndPriorityTest extends TestCase
{
    /**
     * @covers ::__construct()
     * @covers ::getValue()
     * @covers ::getPriority()
     */
    public function testConstructorWithDefaultPriority(): void
    {
        $valueAndPriority = new ValueAndPriority('test');

        static::assertEquals('test', $valueAndPriority->getValue());
        static::assertEquals(Priority::NORMAL, $valueAndPriority->getPriority());
    }

    /**
     * @covers ::__construct()
     * @covers ::getValue()
     * @covers ::getPriority()
     */
    public function testConstructorWithCustomPriority(): void
    {
        $valueAndPriority = new ValueAndPriority('test', Priority::HIGH);

        static::assertEquals('test', $valueAndPriority->getValue());
        static::assertEquals(Priority::HIGH, $valueAndPriority->getPriority());
    }

    /** @covers ::equals() */
    public function testEqualsWithDifferentType(): void
    {
        $valueAndPriority = new ValueAndPriority('test');

        static::assertFalse($valueAndPriority->equals(new stdClass()));
    }

    /** @covers ::equals() */
    public function testEqualsWithSameInstance(): void
    {
        $valueAndPriority = new ValueAndPriority('test');

        static::assertTrue($valueAndPriority->equals($valueAndPriority));
    }

    /** @covers ::equals() */
    public function testEqualsWithSameValue(): void
    {
        $first  = new ValueAndPriority('test', Priority::LOW);
        $second = new ValueAndPriority('test', Priority::HIGH);

        static::assertTrue($first->equals($second));
    }

    /** @covers ::equals() */
    public function testEqualsWithDifferentValue(): void
    {
        $first  = new ValueAndPriority('foo');
        $second = new ValueAndPriority('bar');

        static::assertFalse($first->equals($second));
    }

    /** @covers ::hash() */
    public function testHash(): void
    {
        $first  = new ValueAndPriority('test', Priority::LOW);
        $second = new ValueAndPriority('test', Priority::HIGH);

        static::assertEquals($first->hash(), $second->hash());
    }
}
