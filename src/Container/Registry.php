<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) 2026 Luca Cantoreggi
 */

namespace Vivarium\Container;

interface Registry
{
    public function findProvider(Binding $binding): ?Provider;

    public function findScope(Binding $binding): Scope;

    /** @return iterable<Enhancement> */
    public function findEnhancements(Binding $binding): iterable;
}
