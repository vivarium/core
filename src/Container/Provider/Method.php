<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Container\Provider;

use Vivarium\Container\Binding\ArgumentBinder;
use Vivarium\Container\Provider;

interface Method extends Provider
{
    /** @return ParameterBinder<self> */
    public function bind(string $parameter): ArgumentBinder;
}
