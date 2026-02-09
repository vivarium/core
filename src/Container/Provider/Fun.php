<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) 2026 Luca Cantoreggi
 */

namespace Vivarium\Container\Provider;

use Closure;
use ReflectionFunction;
use Vivarium\Assertion\Boolean\IsTrue;
use Vivarium\Assertion\Comparison\IsEqualsTo;
use Vivarium\Assertion\Type\IsAssignableTo;
use Vivarium\Collection\Set\HashSet;
use Vivarium\Collection\Set\Set;
use Vivarium\Container\Capability;
use Vivarium\Container\Container;
use Vivarium\Container\Provider;
use Vivarium\Type\Type;

use function count;
use function function_exists;
use function is_string;

final class Fun implements Provider
{
    /** @param callable(Container):mixed $fn */
    public function __construct(private Closure|string $fn)
    {
        // TODO: Create IsFunction assertion to validate both Closure and string functions
        if (is_string($fn)) {
            (new IsTrue())
                ->assert(function_exists($fn));
        }

        $parameters = (new ReflectionFunction($fn))
            ->getParameters();

        (new IsEqualsTo(1))
            ->assert(count($parameters));

        (new IsAssignableTo((string) $parameters[0]->getType()))
            ->assert(Container::class);
    }

    public function provide(Container $container): mixed
    {
        return ($this->fn)($container);
    }

    public function getTarget(): string
    {
        $type = (new ReflectionFunction($this->fn))
            ->getReturnType();

        return $type === null ? Type::MIXED : (string) $type;
    }

    public function getCapabilities(): Set
    {
        return HashSet::fromArray([
            Capability::INTERCEPTABLE,
            Capability::DECORABLE,
        ]);
    }
}
