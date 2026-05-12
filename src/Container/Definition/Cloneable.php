<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) 2026 Luca Cantoreggi
 */

namespace Vivarium\Container\Definition;

use Vivarium\Container\Container;
use Vivarium\Container\Definition;
use Vivarium\Container\Enhancement;
use Vivarium\Container\Provider;

final class Cloneable implements Definition
{
    private Transient $transient;

    private mixed $instance;

    public function __construct(Provider $provider)
    {
        $this->transient = new Transient($provider);
        $this->instance  = null;
    }

    public function solve(Container $container): mixed
    {
        if ($this->instance === null) {
            $this->instance = $this->transient->solve($container);
        }

        return clone $this->instance;
    }

    public function withEnhancement(Enhancement $enhancement, int $priority): self
    {
        $clone = clone $this;

        $clone->transient = $this->transient->withEnhancement($enhancement, $priority);

        return $clone;
    }
}
