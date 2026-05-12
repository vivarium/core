<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Container\Binding;

use Vivarium\Assertion\Object\HasProperty;
use Vivarium\Assertion\Type\IsClass;
use Vivarium\Container\Binding;

final class EnhancementBinder
{
    public function __construct(private Binding $binding)
    {
    }

    public function withMethodCall(string $method): void
    {
    }

    public function withImmutableMethodCall(string $method): void
    {
    }

    public function withPropertySet(string $property): void
    {
        (new IsClass())
            ->assert($this->binding->getType());

        (new HasProperty($property))
            ->assert($this->binding->getType());
    }
}
