<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Test\Dispatcher;

use PHPUnit\Framework\TestCase;
use Vivarium\Test\Dispatcher\Stub\StubStoppableEvent;

/** @coversDefaultClass \Vivarium\Dispatcher\StoppableEvent */
final class StoppableEventTest extends TestCase
{
    /**
     * @covers ::__construct()
     * @covers ::stopPropagation()
     * @covers ::isPropagationStopped()
     */
    public function testPropagation(): void
    {
        $event = new StubStoppableEvent();

        static::assertFalse($event->isPropagationStopped());

        $event->stopPropagation();

        static::assertTrue($event->isPropagationStopped());
    }
}
