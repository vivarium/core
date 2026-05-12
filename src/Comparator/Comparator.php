<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

declare(strict_types=1);

namespace Vivarium\Comparator;

/** @template T */
interface Comparator
{
    /**
     * @param T $first
     * @param T $second
     */
    public function compare($first, $second): int;

    /**
     * @param T $first
     * @param T $second
     */
    public function __invoke($first, $second): int;
}
