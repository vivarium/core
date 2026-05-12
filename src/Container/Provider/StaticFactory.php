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
use Vivarium\Assertion\Object\HasPublicMethod;
use Vivarium\Assertion\Type\IsClass;
use Vivarium\Collection\Set\HashSet;
use Vivarium\Collection\Set\Set;
use Vivarium\Container\Capability;
use Vivarium\Container\Container;
use Vivarium\Container\Provider;

final class StaticFactory implements Provider
{
    public function __construct(
        private string $class,
        private string $method,
    ) {
        (new IsClass())
            ->assert($class);

        (new HasPublicMethod($method))
            ->assert($class);
    }

    public function provide(Container $container): mixed
    {
        // TODO: Implement parameter resolution and method call
        throw new RuntimeException('Not implemented yet.');
    }

    public function getTarget(): string
    {
        $type = (new ReflectionClass($this->class))
            ->getMethod($this->method)
            ->getReturnType();

        return $type === null ? 'mixed' : (string) $type;
    }

    public function getCapabilities(): Set
    {
        return HashSet::fromArray([
            Capability::INTERCEPTABLE,
            Capability::DECORABLE,
        ]);
    }
}
