<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

declare(strict_types=1);

namespace Vivarium\Comparator;

/** @template-implements Comparator<int> */
final class IntegerComparator implements Comparator
{
    /**
     * @param int $first
     * @param int $second
     */
    public function compare($first, $second): int
    {
        if ($first < $second) {
            return -1;
        }

        if ($first > $second) {
            return 1;
        }

        return 0;
    }

    /**
     * @param int $first
     * @param int $second
     */
    public function __invoke($first, $second): int
    {
        return $this->compare($first, $second);
    }
}
