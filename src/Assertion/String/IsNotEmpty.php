<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

declare(strict_types=1);

namespace Vivarium\Assertion\String;

use Vivarium\Assertion\Assertion;
use Vivarium\Assertion\Conditional\Not;

/** @template-implements Assertion<non-empty-string> */
final class IsNotEmpty implements Assertion
{
    /** @psalm-var Assertion<string>  */
    private Assertion $assertion;

    public function __construct()
    {
        $this->assertion = new Not(
            new IsEmpty(),
        );
    }

    /** @psalm-assert non-empty-string $value */
    public function assert(mixed $value, string $message = ''): void
    {
        $this->assertion->assert(
            $value,
            ! (new IsEmpty())($message) ?
                $message : 'Expected string to be not empty.',
        );
    }

    /**
     * @psalm-assert string $value
     * @psalm-assert-if-true non-empty-string $value
     */
    public function __invoke(mixed $value): bool
    {
        return ($this->assertion)($value);
    }
}
