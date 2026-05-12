<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Assertion\Type;

use Vivarium\Assertion\Assertion;
use Vivarium\Assertion\Conditional\Either;
use Vivarium\Assertion\String\IsEmpty;

/** @template-implements Assertion<class-string> */
final class IsClassOrInterface implements Assertion
{
    /** @var Assertion<class-string> */
    private Assertion $assertion;

    public function __construct()
    {
        $this->assertion = new Either(
            new IsClass(),
            new IsInterface(),
        );
    }

    /** @psalm-assert class-string $value */
    public function assert(mixed $value, string $message = ''): void
    {
        $message = (new IsEmpty())($message) ?
            'Expected string to be class or interface name. Got %s.' : $message;

        $this->assertion->assert($value, $message);
    }

    /**
     * @psalm-assert string $value
     * @psalm-assert-if-true class-string $value
     */
    public function __invoke(mixed $value): bool
    {
        return ($this->assertion)($value);
    }
}
