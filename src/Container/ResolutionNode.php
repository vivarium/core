<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Container;

use Closure;

interface ResolutionNode
{
    /**
     * @param Closure(): Definition $next
     */
    public function resolve(Binding $binding, Closure $next): Definition;
}
