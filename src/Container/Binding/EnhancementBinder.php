<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MIT
 * Copyright (c) 2025 Luca Cantoreggi
 */

namespace Vivarium\Container;

use Vivarium\Assertion\Object\HasProperty;
use Vivarium\Assertion\Type\IsClass;

final class EnhancementBinder 
{
    private Binding $binding;

    public function __construct(Binding $binding)
    {
        $this->binding = $binding;
    }

    public function withMethodCall(string $method)
    {

    }

    public function withImmutableMethodCall(string $method)
    {

    }

    public function withPropertySet(string $property)
    {
        (new IsClass())
            ->assert($this->binding->getType());

        (new HasProperty($property))
            ->assert($this->binding->getType());
    }
}
