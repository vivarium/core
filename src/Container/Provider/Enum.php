<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Container\Provider;

use ReflectionEnum;
use Vivarium\Assertion\Boolean\IsTrue;
use Vivarium\Collection\Set\HashSet;
use Vivarium\Collection\Set\Set;
use Vivarium\Container\Container;
use Vivarium\Container\Provider;

use function enum_exists;

final class Enum implements Provider
{
    public function __construct(
        private string $enum,
        private string $case,
    ) {
        (new IsTrue())
            ->assert(enum_exists($enum));

        (new IsTrue())
            ->assert((new ReflectionEnum($enum))->hasCase($case));
    }

    public function provide(Container $container): mixed
    {
        return (new ReflectionEnum($this->enum))
            ->getCase($this->case)
            ->getValue();
    }

    public function getTarget(): string
    {
        return $this->enum;
    }

    public function getCapabilities(): Set
    {
        return new HashSet();
    }
}
