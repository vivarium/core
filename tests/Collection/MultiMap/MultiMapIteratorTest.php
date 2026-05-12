<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Test\Collection\MultiMap;

use Vivarium\Collection\Map\MapIterator;
use Vivarium\Collection\MultiMap\MultiMapIterator;
use Vivarium\Collection\Pair\Pair;
use Vivarium\Collection\Sequence\ArraySequence;
use Vivarium\Equality\HashBuilder;
use Vivarium\Test\Collection\Iterator\IteratorTestCase;
use Vivarium\Test\Collection\Stub\Key;

/** @coversDefaultClass \Vivarium\Collection\MultiMap\MultiMapIterator */
final class MultiMapIteratorTest extends IteratorTestCase
{
    /**
     * @covers ::__construct()
     * @covers ::rewind()
     * @covers ::valid()
     * @covers ::key()
     * @covers ::current()
     * @covers ::next()
     */
    public function testMultiMapIterator(): void
    {
        $map = static function (int $key): Key {
            return new Key($key);
        };

        $values = [
            1 => new ArraySequence(1, 2),
            3 => new ArraySequence(4, 5),
        ];

        $pairs = [];
        foreach ($values as $key => $value) {
            $hash = (new HashBuilder())
                ->append($map($key))
                ->getHashCode();

            $pairs[$hash] = new Pair($map($key), $value);
        }

        $iterator = new MultiMapIterator(
            new MapIterator($pairs),
        );

        $this->doTest($iterator, [1, 1, 3, 3], [1, 2, 4, 5], $map);
    }
}
