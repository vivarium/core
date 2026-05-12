<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Dispatcher;

use Vivarium\Equality\Equality;
use Vivarium\Equality\EqualsBuilder;
use Vivarium\Equality\HashBuilder;

/** @template T as Event */
final class ListenerAndPriority implements Equality
{
    /** @param EventListener<T> $listener */
    public function __construct(private EventListener $listener, private int $priority)
    {
    }

    /** @return EventListener<T> */
    public function getEventListener(): EventListener
    {
        return $this->listener;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function equals(object $object): bool
    {
        if (! $object instanceof ListenerAndPriority) {
            return false;
        }

        if ($object === $this) {
            return true;
        }

        return (new EqualsBuilder())
            ->append($this->listener, $object->listener)
            ->append($this->priority, $object->priority)
            ->isEquals();
    }

    public function hash(): string
    {
        return (new HashBuilder())
            ->append($this->listener)
            ->append($this->priority)
            ->getHashCode();
    }
}
