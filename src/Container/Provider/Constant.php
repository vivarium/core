<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) 2026 Luca Cantoreggi
 */

namespace Vivarium\Container\Provider;

use Vivarium\Assertion\Boolean\IsTrue;
use Vivarium\Collection\Set\HashSet;
use Vivarium\Collection\Set\Set;
use Vivarium\Container\Binding;
use Vivarium\Container\Capability;
use Vivarium\Container\Provider;
use Vivarium\Container\Container;

use function defined;

final class Constant implements Provider
{
    public function __construct(private string $name)
    {
        (new IsTrue())
            ->assert(defined($name));
    }

    public function provide(Container $container): mixed
    {
        return (new ReflectionConstant($this->name))
            ->getValue();
    }

    public function getTarget(): string
    {
        return gettype(
            (new ReflectionConstant($this->name))
                ->getValue()
        );
    }

    public function getCapabilities(): Set
    {
        return HashSet::fromArray([
            Capability::INJECTABLE
        ]);
    }
}
