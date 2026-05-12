<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Container;

interface Definition
{
    public function solve(Container $container): mixed;

    public function withEnhancement(Enhancement $enhancement, int $priority): self;
}
