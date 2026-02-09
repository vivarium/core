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
use Vivarium\Container\Container;
use Vivarium\Container\Provider;
use Vivarium\Type\Type;

use function constant;
use function defined;
use function gettype;

final class Constant implements Provider
{
    public function __construct(private string $name)
    {
        // TODO: Create HasConstant assertion for global constants
        (new IsTrue())
            ->assert(defined($name));
    }

    public function provide(Container $container): mixed
    {
        return constant($this->name);
    }

    public function getTarget(): string
    {
        return Type::normalize(
            gettype(constant($this->name)),
        );
    }

    public function getCapabilities(): Set
    {
        return new HashSet();
    }
}
