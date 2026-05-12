<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) 2026 Luca Cantoreggi
 */

namespace Vivarium\Container;

interface Container
{
    public function get(string $id, string $context = Binding::GLOBAL, string $tag = Binding::DEFAULT): mixed;

    public function has(string $id, string $context = Binding::GLOBAL, string $tag = Binding::DEFAULT): bool;
}
