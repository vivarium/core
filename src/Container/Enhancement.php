<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) 2026 Luca Cantoreggi
 */

namespace Vivarium\Container;

interface Enhancement
{
    /**
     * @param T $instance
     * 
     * @return T
     */
    public function enhance(mixed $instance, Container $container): mixed;

    public function accept(Provider $provider) : bool;
}
