<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) 2026 Luca Cantoreggi
 */

declare(strict_types=1);

namespace Vivarium\Container\Enhancement;

use ReflectionClass;
use Vivarium\Assertion\Object\HasProperty;
use Vivarium\Assertion\Type\IsAssignableTo;
use Vivarium\Assertion\Var\IsObject;
use Vivarium\Check\CheckIfObject;
use Vivarium\Container\Binding;
use Vivarium\Container\Capability;
use Vivarium\Container\Container;
use Vivarium\Container\Enhancement;
use Vivarium\Container\Provider;

final class SetProperty implements Enhancement
{
    private string $property;

    private Binding $dependency;

    public function __construct(string $property, Binding $dependency)
    {
        $this->property   = $property;
        $this->dependency = $dependency;
    }

    public function enhance(mixed $instance, Container $container): mixed
    {
        (new IsObject())
            ->assert($instance);

        (new HasProperty($this->property))
            ->assert($instance);

        $property = (new ReflectionClass($instance))
            ->getProperty($this->property);

        $type = $property->hasType() ? 
            (string) $property->getType() :
            'mixed';

        (new IsAssignableTo($type))
            ->assert($this->dependency->getType());

        $property->setValue(
            $instance, 
            $container->get($this->dependency)
        );

        return $instance;
    }

    public function accept(Provider $provider): bool
    {
        $hasProperty = CheckIfObject::hasProperty(
            $provider->getTarget(),
            $this->property
        );

        $isEnhanceable =
            $provider->getCapabilities()->contains(Capability::INJECTABLE) ||
            $provider->getCapabilities()->contains(Capability::INTERCEPTABLE);

        return $hasProperty && $isEnhanceable;
    }
}
