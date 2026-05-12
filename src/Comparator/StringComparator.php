<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

declare(strict_types=1);

namespace Vivarium\Comparator;

use function strcmp;

/** @template-implements Comparator<string> */
final class StringComparator implements Comparator
{
    /**
     * @param string $first
     * @param string $second
     */
    public function compare($first, $second): int
    {
        return strcmp($first, $second);
    }

    /**
     * @param string $first
     * @param string $second
     */
    public function __invoke($first, $second): int
    {
        return $this->compare($first, $second);
    }
}
