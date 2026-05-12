<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

declare(strict_types=1);

namespace Vivarium\Collection\Pair;

use Vivarium\Equality\Equality;
use Vivarium\Equality\EqualsBuilder;
use Vivarium\Equality\HashBuilder;

/**
 * @template-covariant K
 * @template-covariant V
 */
final class Pair implements Equality
{
    /**
     * @param K $key
     * @param V $value
     */
    public function __construct(private $key, private $value)
    {
    }

    /** @return K */
    public function getKey()
    {
        return $this->key;
    }

    /** @return V */
    public function getValue()
    {
        return $this->value;
    }

    public function equals(object $object): bool
    {
        if (! $object instanceof Pair) {
            return false;
        }

        if ($this === $object) {
            return true;
        }

        return (new EqualsBuilder())
            ->append($this->getKey(), $object->getKey())
            ->append($this->getValue(), $object->getValue())
            ->isEquals();
    }

    public function hash(): string
    {
        return (new HashBuilder())
            ->append($this->key)
            ->append($this->value)
            ->getHashCode();
    }
}
