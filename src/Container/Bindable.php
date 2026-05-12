<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) 2026 Luca Cantoreggi
 */

declare(strict_types=1);

namespace Vivarium\Container;

use Vivarium\Container\Binding;
use Vivarium\Container\Binding\DecoratorBinder;
use Vivarium\Container\Binding\EnhancementBinder;
use Vivarium\Container\Binding\InterceptionBinder;
use Vivarium\Container\Binding\ProviderBinder;
use Vivarium\Container\Binding\ScopeBinder;
/**
 * @template T
 */
interface Bindable
{
    /**
     * @return ProviderBinder<T>
     */
    public function bind(
        string $type, 
        string $tag = Binding::DEFAULT, 
        string $context = Binding::GLOBAL
    ) : ProviderBinder;

    /**
     * @return EnhancementBinder<T>
     */
    public function inject(
        string $type,
        string $tag = Binding::DEFAULT, 
        string $context = Binding::GLOBAL
    ) : EnhancementBinder;

    /**
     * @return EnhancementBinder<T>
     */
    public function enhance(
        string $type,
        string $tag = Binding::DEFAULT, 
        string $context = Binding::GLOBAL
    ) : EnhancementBinder;

    /**
     * @return DecoratorBinder<T>
     */
    public function decorate(
        string $type,
        string $tag = Binding::DEFAULT, 
        string $context = Binding::GLOBAL
    ) : DecoratorBinder;

    /**
     * @return ScopeBinder<T>
     */
    public function scope(
        string $type, 
        string $tag = Binding::DEFAULT, 
        string $context = Binding::GLOBAL
    ): ScopeBinder;
}
