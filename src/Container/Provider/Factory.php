<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Container\Provider;

use ReflectionClass;
use RuntimeException;
use Vivarium\Assertion\Object\HasMethod;
use Vivarium\Collection\Set\HashSet;
use Vivarium\Collection\Set\Set;
use Vivarium\Container\Binding;
use Vivarium\Container\Capability;
use Vivarium\Container\Container;
use Vivarium\Container\Provider;
use Vivarium\Type\Type;

final class Factory implements Provider
{
    public function __construct(
        private Binding $factory,
        private string $method,
    ) {
        (new HasMethod($method))
            ->assert($factory->getType());
    }

    public function provide(Container $container): mixed
    {
        $factory = $container->get($this->factory);

        throw new RuntimeException('Not implemented yet.');
    }

    public function getTarget(): string
    {
        $type = (new ReflectionClass($this->factory->getType()))
            ->getMethod($this->method)
            ->getReturnType();

        return $type === null ? Type::MIXED : $type->getName();
    }

    public function getCapabilities(): Set
    {
        return HashSet::fromArray([
            Capability::INTERCEPTABLE,
            Capability::DECORABLE,
        ]);
    }
}
