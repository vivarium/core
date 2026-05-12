<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Test\Dispatcher;

use PHPUnit\Framework\TestCase;
use Vivarium\Dispatcher\ClosureListener;
use Vivarium\Dispatcher\Event;

/** @coversDefaultClass \Vivarium\Dispatcher\ClosureListener */
final class ClosureListenerTest extends TestCase
{
    /**
     * @covers ::__construct()
     * @covers ::handle()
     */
    public function testCallingClosure(): void
    {
        $mock = $this->createMock(Event::class);

        $listener = new ClosureListener(static function (Event $event) use ($mock): Event {
            static::assertSame($mock, $event);

            return $event;
        });

        $event = $listener->handle($mock);

        static::assertSame($mock, $event);
    }
}
