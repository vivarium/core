<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MIT
 * Copyright (c) 2025 Luca Cantoreggi
 */

namespace Vivarium\Container\Definition;

use Vivarium\Container\Container;
use Vivarium\Container\Definition;
use Vivarium\Container\Provider;

final class Transient implements Definition
{
    private Provider $provider;

    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
    }

    public function solve(Container $container): mixed
    {
        return $this->provider->provide($container);
    }
}