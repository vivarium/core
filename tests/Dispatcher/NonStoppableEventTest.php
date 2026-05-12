<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Test\Dispatcher;

use PHPUnit\Framework\TestCase;
use Vivarium\Test\Dispatcher\Stub\StubNonStoppableEvent;

/** @coversDefaultClass \Vivarium\Dispatcher\NonStoppableEvent */
final class NonStoppableEventTest extends TestCase
{
    /** @covers ::isPropagationStopped() */
    public function testPropagation(): void
    {
        $event = new StubNonStoppableEvent();

        static::assertFalse($event->isPropagationStopped());
    }
}
