<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MIT
 * Copyright (c) 2025 Luca Cantoreggi
 */

namespace Vivarium\Container\Provider;

use Vivarium\Container\Provider;
use Vivarium\Container\Binding\ArgumentBinder;

interface Method extends Provider
{
    /** @return ParameterBinder<self> */
    public function bind(string $parameter) : ArgumentBinder;
}
