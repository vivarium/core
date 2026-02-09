<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) 2026 Luca Cantoreggi
 */

namespace Vivarium\Container\Provider;

use ReflectionClass;
use Vivarium\Assertion\Boolean\IsTrue;
use Vivarium\Assertion\Type\IsClassOrInterface;
use Vivarium\Collection\Set\HashSet;
use Vivarium\Collection\Set\Set;
use Vivarium\Container\Container;
use Vivarium\Container\Provider;
use Vivarium\Type\Type;

use function gettype;

final class ClassConstant implements Provider
{
    public function __construct(
        private string $class,
        private string $name,
    ) {
        (new IsClassOrInterface())
            ->assert($class);

        // TODO: Create HasConstant assertion
        (new IsTrue())
            ->assert(
                (new ReflectionClass($class))->hasConstant($name),
            );
    }

    public function provide(Container $container): mixed
    {
        return (new ReflectionClass($this->class))
            ->getReflectionConstant($this->name)
            ->getValue();
    }

    public function getTarget(): string
    {
        return Type::normalize(
            gettype(
                (new ReflectionClass($this->class))
                    ->getReflectionConstant($this->name)
                    ->getValue(),
            ),
        );
    }

    public function getCapabilities(): Set
    {
        return new HashSet();
    }
}
