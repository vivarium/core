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
use Vivarium\Container\Provider;

final class Cloneable implements Definition
{
    private Provider $provider;

    private mixed $instance;

    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
        $this->instance = NULL;
    }

    public function solve(Container $container): mixed
    {
        if ($this->instance === NULL) {
            $this->instance = $this->provider->provide($container);
        }

        return clone $this->instance;
    }
}
