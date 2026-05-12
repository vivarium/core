<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Test\Collection\Util;

use PHPUnit\Framework\TestCase;
use Vivarium\Collection\Util\KeyHash;
use Vivarium\Equality\HashBuilder;
use Vivarium\Test\Collection\Stub\Key;

/** @coversDefaultClass \Vivarium\Collection\Util\KeyHash */
final class KeyHashTest extends TestCase
{
    /** @covers ::hash() */
    public function testHash(): void
    {
        $hash = (new HashBuilder())
            ->append(new Key(42))
            ->getHashCode();

        static::assertSame(1, KeyHash::hash(1));
        static::assertSame('str', KeyHash::hash('str'));
        static::assertSame($hash, KeyHash::hash(new Key(42)));
    }
}
