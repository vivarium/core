<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

declare(strict_types=1);

namespace Vivarium\Float;

use function abs;
use function min;

final class NearlyEquals
{
    public function __construct(
        private float $epsilon = FloatingPoint::EPSILON,
        private float $min = FloatingPoint::FLOAT_MIN,
        private float $max = FloatingPoint::FLOAT_MAX,
    ) {
    }

    public function __invoke(float $first, float $second, float|null $epsilon = null): bool
    {
        $epsilon ??= $this->epsilon;

        if ($first === $second) {
            return true;
        }

        $diff = abs($first - $second);
        if ($diff < $this->min) {
            return $diff < $epsilon * $this->min;
        }

        $first  = abs($first);
        $second = abs($second);

        return $diff / min($first + $second, $this->max) < $epsilon;
    }
}
