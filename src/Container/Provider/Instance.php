<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) 2026 Luca Cantoreggi
 */

namespace Vivarium\Container\Provider;

use Vivarium\Collection\Set\HashSet;
use Vivarium\Collection\Set\Set;
use Vivarium\Container\Capability;
use Vivarium\Container\Provider;
use Vivarium\Container\Container;

use function gettype;

final class Instance implements Provider
{
    private mixed $instance;

    public function __construct(mixed $instance)
    {
        $this->instance = $instance;
    }

    public function provide(Container $container): mixed
    {
        return $this->instance;
    }

    public function getTarget(): string
    {
        return gettype($this->instance);
    }

    public function getCapabilities(): Set
    {
        return HashSet::fromArray([
            Capability::DECORABLE
        ]);
    }
}