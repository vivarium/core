<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) 2026 Luca Cantoreggi
 */

namespace Vivarium\Container\Provider;

use Vivarium\Assertion\Object\HasMethod;
use Vivarium\Assertion\Type\IsClass;
use Vivarium\Collection\Map\HashMap;
use Vivarium\Collection\Set\HashSet;
use Vivarium\Collection\Set\Set;
use Vivarium\Container\Binding\ArgumentBinder;
use Vivarium\Container\Capability;
use Vivarium\Container\Container;
use Vivarium\Container\Provider;

final class Constructor implements Method
{
    /** @var HashMap<string, Provider> */
    private HashMap $arguments;

    public function __construct(private string $class)
    {
        (new IsClass())
            ->assert($class);

        (new HasMethod('__construct'))
            ->assert($class);
    }

    public function provide(Container $container): mixed
    {
    }

    public function getTarget(): string
    {
        return $this->class;
    }

    public function getCapabilities(): Set
    {
        return HashSet::fromArray([
            Capability::INJECTABLE,
            Capability::INTERCEPTABLE,
            Capability::DECORABLE,
        ]);
    }

    /** @return ArgumentBinder<Constructor> */
    public function bind(string $parameter): ArgumentBinder
    {
        return new ArgumentBinder(function (Provider $provider) use ($parameter) {
            $constructor            = clone $this;
            $constructor->arguments = $this->arguments->put(
                $parameter,
                $provider,
            );

            return $constructor;
        });
    }
}
