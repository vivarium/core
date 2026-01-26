<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) 2026 Luca Cantoreggi
 */

namespace Vivarium\Container\Provider;

use Closure;
use Reflection;
use ReflectionFunction;
use Vivarium\Assertion\Boolean\IsTrue;
use Vivarium\Assertion\Conditional\IsNotNull;
use Vivarium\Assertion\Type\IsAssignableTo;
use Vivarium\Collection\Set\HashSet;
use Vivarium\Collection\Set\Set;
use Vivarium\Container\Capability;
use Vivarium\Container\Provider;
use Vivarium\Container\Container;

final class Fun implements Provider
{
    /** 
     * @param callable(Container):mixed $fn 
     */
    public function __construct(private Closure|string $fn)
    {
        (new IsTrue())
            ->assert(\function_exists($fn));

        $parameters = (new ReflectionFunction($fn))
            ->getParameters();

        (new IsTrue())
            ->assert(count($parameters) === 1);

        $argument = $parameters[0]
            ->getDeclaringClass()
            ->getName();

        (new IsNotNull())
            ->assert($argument);

        (new IsAssignableTo($argument))
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

        return $type === NULL ? 'mixed' : $type->getName();
    }

    public function getCapabilities(): Set
    {
        return HashSet::fromArray([
            Capability::INTERCEPTABLE,
            Capability::DECORABLE
        ]);
    }
}
