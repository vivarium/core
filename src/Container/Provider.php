<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MIT
 * Copyright (c) 2025 Luca Cantoreggi
 */

namespace Vivarium\Container;

use Vivarium\Collection\Set\Set;

interface Provider
{
    public function provide(Container $container) : mixed;

    public function getTarget() : string;

    /** 
     * @return Set<Capability> 
     */
    public function getCapabilities() : Set;
}
