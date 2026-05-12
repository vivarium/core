<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Container;

use ReflectionClass;
use Vivarium\Check\CheckIfType;
use Vivarium\Collection\Map\HashMap;
use Vivarium\Collection\MultiMap\MultiMap;
use Vivarium\Collection\MultiMap\MultiValueMap;
use Vivarium\Collection\Sequence\ArraySequence;
use Vivarium\Container\Binding\ProviderBinder;
use Vivarium\Container\Binding\ScopeBinder;

final class Injector implements Container, Bindable
{
    /** @var HashMap<Binding, Provider> */
    private HashMap $providers;

    /** @var MultiMap<Binding, ValueAndPriority<Interception>> */
    private MultiMap $injections;

    /** @var MultiMap<Binding, ValueAndPriority<Interception>> */
    private MultiMap $interceptions;

    /** @var HashMap<Binding, Scope> */
    private HashMap $scopes;

    public function __construct()
    {
        $this->providers = new HashMap();

        $this->injections = new MultiValueMap(function () {
            return new ArraySequence();
        });
        
        $this->interceptions = new MultiValueMap(function () {
            return new ArraySequence();
        });

        $this->scopes = new HashMap();
    }

    public function get(Binding $binding)
    {

    }

    public function has(Binding $binding) : bool
    {
        if ($this->providers->containsKey($binding)) {
            return true;
        }

        if (! CheckIfType::IsClass($binding->getId())) {
            return false;
        }

        return (new ReflectionClass($binding->getId()))
            ->isInstantiable();
    }

    /**
     * @return ProviderBinder<Injector>
     */
    public function bind(
        string $type, 
        string $tag = Binding::DEFAULT, 
        string $context = Binding::GLOBAL
    ) : ProviderBinder
    {
        return new ProviderBinder(
            function (Binding $binding, Provider $provider) {
                $container            = clone $this;
                $container->providers = $this->providers->put(
                    $binding,
                    $provider
                );

                return $container;
            },
            new Binding($type, $tag, $context)
        );
    }

    /**
     * @return InjectionBinder<T>
     */
    public function inject(
        string $type,
        string $tag = Binding::DEFAULT, 
        string $context = Binding::GLOBAL
    ) : InterceptionBinder
    {

    }

    /**
     * @return InterceptionBinder<T>
     */
    public function enhance(
        string $type,
        string $tag = Binding::DEFAULT, 
        string $context = Binding::GLOBAL
    ) : InterceptionBinder
    {

    }

    /**
     * @return DecoratorBinder<T>
     */
    public function decorate(
        string $type,
        string $tag = Binding::DEFAULT, 
        string $context = Binding::GLOBAL
    ) : DecoratorBinder
    {

    }

    /**
     * @return ScopeBinder<Injector>
     */
    public function scope(
        string $type, 
        string $tag = Binding::DEFAULT, 
        string $context = Binding::GLOBAL
    ): ScopeBinder
    {
        return new ScopeBinder(
            function (Scope $scope) use ($type, $tag, $context) {
                $container         = clone $this;
                $container->scopes = $this->scopes->put(
                    new Binding($type, $tag, $context),
                    $scope
                );

                return $container;
            },
            
        );
    }
}
