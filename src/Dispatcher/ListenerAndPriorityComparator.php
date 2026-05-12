<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Dispatcher;

use Vivarium\Comparator\Comparator;

/** @template-implements Comparator<ListenerAndPriority<Event>> */
final class ListenerAndPriorityComparator implements Comparator
{
    /**
     * @param ListenerAndPriority<Event> $first
     * @param ListenerAndPriority<Event> $second
     */
    public function compare($first, $second): int
    {
        return $second->getPriority() - $first->getPriority();
    }

    /**
     * @param ListenerAndPriority<Event> $first
     * @param ListenerAndPriority<Event> $second
     */
    public function __invoke($first, $second): int
    {
        return $this->compare($first, $second);
    }
}
