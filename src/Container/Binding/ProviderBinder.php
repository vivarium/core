<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) 2026 Luca Cantoreggi
 */

declare(strict_types=1);

namespace Vivarium\Container\Binding;

use Vivarium\Assertion\Boolean\IsTrue;
use Vivarium\Assertion\Conditional\IsNotNull;
use Vivarium\Assertion\Type\IsAssignableTo;
use Vivarium\Container\Binding;
use Vivarium\Container\Provider;
use Vivarium\Container\Provider\Constant;
use Vivarium\Container\Provider\Constructor;
use Vivarium\Container\Provider\ContainerCall;
use Vivarium\Container\Provider\Factory;
use Vivarium\Container\Provider\StaticFactory;
use Vivarium\Container\Provider\Instance;
use Vivarium\Container\Provider\Enum;

use \ReflectionFunction;

/**
 * @template T of Bindable
 */
final class ProviderBinder
{
    /** @var callable (Binding, Provider):T */
    private $create;

    private Binding $source;

    /** 
     * @param callable(Binding, Provider):T $name
     */
    public function __construct(callable $create, Binding $source)
    {
        (new IsNotNull())
            ->assert(
                (new ReflectionFunction($create))->getReturnType(),
                '"Missing type hint on callback function."',
            );

        $this->create = $create;
        $this->source = $source;
    }

    /**
     * @return T
     */
    public function to(
        string $id,
        string $tag = Binding::DEFAULT,
        string $context = Binding::GLOBAL,)
    {
        (new IsAssignableTo($this->source->getId()))
            ->assert($id);

        return $this->toProvider(
            new ContainerCall(
                new Binding($id, $tag, $context)
            )
        );
    }
    
    /**
     * @return ScopeBinder<T>
     */
    public function toConstructor() : ScopeBinder
    {
        return $this
            ->toProvider(
                new Constructor()
            )
            ->scope(
                $this->source
            );
    }

    /**
     * @return MethodBinder<T>
     */    
    public function toFactory(
        string $class,
        string $tag = Binding::DEFAULT,
        string $context = Binding::GLOBAL,
    ): MethodBinder {
        $binding = new Binding($class, $tag, $context);

        return new MethodBinder(function (string $method, callable $configure) use ($binding) {
            return $this->toProvider(
                $configure(
                    new Factory(
                        $binding,
                        $method
                    )
                )
            );
        });
    }

    /**
     * @return MethodBinder<T>
     */    
    public function toStaticFactory(
        string $class,
        string $tag = Binding::DEFAULT,
        string $context = Binding::GLOBAL,
    ): MethodBinder {
        $binding = new Binding($class, $tag, $context);

        return new MethodBinder(function (string $method, callable $configure) use ($binding) {
            return $this->toProvider(
                $configure(
                    new StaticFactory(
                        $binding,
                        $method
                    )
                )
            );
        });
    }

    /**
     * @return T
     */
    public function toInstance(mixed $instance)
    {
        (new IsNotNull())
            ->assert($instance);
            
        (new IsAssignableTo($this->source->getId()))
            ->assert($instance);

        return $this->toProvider(
            new Instance($instance)
        );
    }

    /**
     * @return T
     */
    public function toConstant(string $constant)
    {
        (new IsTrue())
            ->assert(defined($constant));

        return $this->toProvider(
            new Constant($constant)
        );
    }

    /** 
     * @return T
     */
    public function toEnum(string $enum)
    {
        (new IsTrue())
            ->assert(\enum_exists($enum));

        return $this->toProvider(
            new Enum($enum)
        );
    }

    /** 
     * @return T
     */
    public function toProvider(Provider $provider) : mixed
    {
        return ($this->create)(
            $this->source,
            $provider
        );
    }
}
