<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MIT
 * Copyright (c) 2025 Luca Cantoreggi
 */

namespace Vivarium\Container\Provider;

use Vivarium\Collection\Set\HashSet;
use Vivarium\Collection\Set\Set;
use Vivarium\Container\Binding;
use Vivarium\Container\Capability;
use Vivarium\Container\Provider;
use Vivarium\Container\Container;

final class ContainerCall implements Provider
{
    private Binding $target;

    public function __construct(Binding $target)
    {
        $this->target = $target;
    }

    public function provide(Container $container): mixed
    {
        return $container->get(
            $this->target
        );
    }

    public function getTarget(): string
    {
        return $this->target->getType();
    }

    public function getCapabilities(): Set
    {
        return HashSet::fromArray([
            Capability::INJECTABLE,
            Capability::DECORABLE
        ]);
    }
}
