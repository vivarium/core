<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MIT
 * Copyright (c) 2026 Luca Cantoreggi
 */

declare(strict_types=1);

namespace Vivarium\Assertion\Var;

use Closure;
use Vivarium\Assertion\Assertion;
use Vivarium\Assertion\Exception\AssertionFailed;
use Vivarium\Assertion\String\IsEmpty;
use Vivarium\Type\Type;

use function function_exists;

/** @template-implements Assertion<Closure|string> */
final class IsFunction implements Assertion
{
    /** @psalm-assert Closure|string $value */
    public function assert(mixed $value, string $message = ''): void
    {
        if (! $this($value)) {
            $message = sprintf(
                ! (new IsEmpty())($message) ?
                    $message : 'Expected value to be a function. Got %2$s.',
                Type::toLiteral($value),
                Type::toString($value),
            );

            throw new AssertionFailed($message);
        }
    }

    /** @psalm-assert-if-true Closure|string $value */
    public function __invoke(mixed $value): bool
    {
        if ($value instanceof Closure) {
            return true;
        }

        if (is_string($value)) {
            return function_exists($value);
        }

        return false;
    }
}