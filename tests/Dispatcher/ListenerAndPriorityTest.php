<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Test\Dispatcher;

use PHPUnit\Framework\TestCase;
use stdClass;
use Vivarium\Dispatcher\ListenerAndPriority;
use Vivarium\Dispatcher\Priority;
use Vivarium\Test\Dispatcher\Stub\GenericEventListener;
use Vivarium\Test\Dispatcher\Stub\SpecificEventListener;

/** @coversDefaultClass \Vivarium\Dispatcher\ListenerAndPriority */
final class ListenerAndPriorityTest extends TestCase
{
    /**
     * @covers ::__construct()
     * @covers ::getEventListener()
     * @covers ::getPriority()
     */
    public function testObjectConstruction(): void
    {
        $listener = new GenericEventListener();

        $listenerAndPriority = new ListenerAndPriority($listener, Priority::VERY_HIGH);

        static::assertSame($listener, $listenerAndPriority->getEventListener());
        static::assertSame(Priority::VERY_HIGH, $listenerAndPriority->getPriority());
    }

    /**
     * @covers ::__construct()
     * @covers ::equals()
     */
    public function testEquality(): void
    {
        $listenerAndPriority1 = new ListenerAndPriority(new GenericEventListener(), Priority::VERY_HIGH);
        $listenerAndPriority2 = new ListenerAndPriority(new GenericEventListener(), Priority::VERY_HIGH);
        $listenerAndPriority3 = new ListenerAndPriority(new GenericEventListener(), Priority::HIGH);
        $listenerAndPriority4 = new ListenerAndPriority(new SpecificEventListener(), Priority::VERY_HIGH);

        static::assertTrue($listenerAndPriority1->equals($listenerAndPriority1));
        static::assertTrue($listenerAndPriority1->equals($listenerAndPriority2));
        static::assertFalse($listenerAndPriority1->equals($listenerAndPriority3));
        static::assertFalse($listenerAndPriority1->equals($listenerAndPriority4));
        static::assertFalse($listenerAndPriority1->equals(new stdClass()));
    }

    /**
     * @covers ::__construct()
     * @covers ::hash()
     */
    public function testHash(): void
    {
        $listenerAndPriority1 = new ListenerAndPriority(new GenericEventListener(), Priority::VERY_HIGH);
        $listenerAndPriority2 = new ListenerAndPriority(new GenericEventListener(), Priority::VERY_HIGH);
        $listenerAndPriority3 = new ListenerAndPriority(new GenericEventListener(), Priority::HIGH);
        $listenerAndPriority4 = new ListenerAndPriority(new SpecificEventListener(), Priority::VERY_HIGH);

        static::assertSame($listenerAndPriority1->hash(), $listenerAndPriority1->hash());
        static::assertSame($listenerAndPriority1->hash(), $listenerAndPriority2->hash());
        static::assertNotSame($listenerAndPriority1->hash(), $listenerAndPriority3->hash());
        static::assertNotSame($listenerAndPriority1->hash(), $listenerAndPriority4->hash());
    }
}
