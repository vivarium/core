<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) 2026 Luca Cantoreggi
 */

namespace Vivarium\Container\Binding;

use Vivarium\Container\Provider;
use Vivarium\Container\Binding;
use Vivarium\Assertion\Conditional\IsNotNull;
use \ReflectionFunction;
use Vivarium\Container\Provider\ContainerCall;
use Vivarium\Container\Provider\Instance;

/**
 * @template T of Method
 */
final class ArgumentBinder
{
    /** @var callable(Provider):T */
    private $create;

    /**
     * @param callable(Provider):T $create
     * 
     * @return T
     */
    public function __construct(callable $create)
    {
        (new IsNotNull())
            ->assert(
                (new ReflectionFunction($create))->getReturnType(),
                '"Missing type hint on callback function."',
            );

        $this->create    = $create;
    }

    /**
     * @return T
     */
    public function to(
        string $class,
        string $tag = Binding::DEFAULT,
        string $context = Binding::GLOBAL,
    )
    {
        return ($this->create)(
            new ContainerCall(
                new Binding($class, $tag, $context)
            )
        );
    }

    /**
     * @return T
     */
    public function toInstance(mixed $instance)
    {
        return ($this->create)(
            new Instance($instance)
        );
    }
}
