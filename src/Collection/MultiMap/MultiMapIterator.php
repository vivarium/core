<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Collection\MultiMap;

use Iterator;
use Vivarium\Collection\Collection;

use function assert;

/**
 * @template K
 * @template V
 * @template-implements Iterator<K, V>
 */
final class MultiMapIterator implements Iterator
{
    /** @var Iterator<int, V> */
    private Iterator|null $iterator;

    /** @param Iterator<K, Collection<V>> $mapIterator */
    public function __construct(private Iterator $mapIterator)
    {
        $this->iterator = null;
    }

    /** @return V */
    public function current(): mixed
    {
        if ($this->iterator === null) {
            $this->iterator = $this->mapIterator->current()
                                                ->getIterator();
        }

        return $this->iterator->current();
    }

    public function next(): void
    {
        assert($this->iterator !== null);

        $this->iterator->next();
        if ($this->iterator->valid()) {
            return;
        }

        $this->mapIterator->next();
        if (! $this->mapIterator->valid()) {
            return;
        }

        $this->iterator = $this->mapIterator->current()
                                            ->getIterator();
    }

    /** @return K */
    public function key(): mixed
    {
        return $this->mapIterator->key();
    }

    public function valid(): bool
    {
        return $this->mapIterator->valid();
    }

    public function rewind(): void
    {
        $this->mapIterator->rewind();
        $this->iterator = null;
    }
}
