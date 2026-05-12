<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Test\Collection\Stub;

use Vivarium\Equality\Equality;
use Vivarium\Equality\EqualsBuilder;
use Vivarium\Equality\HashBuilder;

final class KeyWithHashCollision implements Equality
{
    public function __construct(private int $n)
    {
    }

    public function equals(object $object): bool
    {
        if (! $object instanceof KeyWithHashCollision) {
            return false;
        }

        if ($object === $this) {
            return true;
        }

        return (new EqualsBuilder())
            ->append($this->n, $object->n)
            ->isEquals();
    }

    public function hash(): string
    {
        if ($this->n === 1 || $this->n === 2 || $this->n === 3) {
            return '1';
        }

        return (new HashBuilder())
            ->append($this->n)
            ->getHashCode();
    }
}
