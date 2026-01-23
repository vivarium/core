<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MIT
 * Copyright (c) 2025 Luca Cantoreggi
 */

declare(strict_types=1);

namespace Vivarium\Assertion\Type;

use Vivarium\Assertion\Assertion;
use Vivarium\Assertion\Exception\AssertionFailed;
use Vivarium\Assertion\String\IsEmpty;
use Vivarium\Assertion\Var\IsString;
use Vivarium\Type\Type;

use function sprintf;
use function str_starts_with;
use function substr;

/** @template-implements Assertion<non-empty-string> */
final class IsNullable implements Assertion
{
    /** @psalm-assert non-empty-string $value */
    public function assert(mixed $value, string $message = ''): void
    {
        if (! $this($value)) {
            $message = sprintf(
                ! (new IsEmpty())($message) ?
                    $message : 'Expected string to be a nullable type (starting with ?). Got %s.',
                Type::toLiteral($value),
            );

            throw new AssertionFailed($message);
        }
    }

    /** @psalm-assert-if-true non-empty-string $value */
    public function __invoke(mixed $value): bool
    {
        (new IsString())
            ->assert($value);

        if (! str_starts_with($value, '?')) {
            return false;
        }

        return (new IsBasicType())(substr($value, 1));
    }
}
