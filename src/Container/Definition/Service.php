<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Container\Definition;

use Vivarium\Container\Container;
use Vivarium\Container\Definition;
use Vivarium\Container\Enhancement;
use Vivarium\Container\Provider;

final class Service implements Definition
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

        return $this->instance;
    }

    public function withEnhancement(Enhancement $enhancement, int $priority): self
    {
        $clone = clone $this;

        $clone->transient = $this->transient->withEnhancement($enhancement, $priority);

        return $clone;
    }
}
