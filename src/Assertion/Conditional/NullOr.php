<?php

/**
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

declare(strict_types=1);

namespace Vivarium\Assertion\Conditional;

use Vivarium\Assertion\Assertion;

/**
 * @template T
 * @template-implements Assertion<T|null>
 */
final class NullOr implements Assertion
{
    /** @param Assertion<T> $assertion */
    public function __construct(private Assertion $assertion)
    {
    }

    /** @psalm-assert T|null $value */
    public function assert(mixed $value, string $message = ''): void
    {
        if ($value === null) {
            return;
        }

        $this->assertion->assert($value, $message);
    }

    /** @psalm-assert-if-true T|null $value */
    public function __invoke(mixed $value): bool
    {
        return $value === null || ($this->assertion)($value);
    }
}
