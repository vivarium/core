<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

declare(strict_types=1);

namespace Vivarium\Assertion\Type;

use Vivarium\Assertion\Assertion;
use Vivarium\Assertion\Exception\AssertionFailed;
use Vivarium\Assertion\String\IsEmpty;
use Vivarium\Type\Type;

use function sprintf;
use function substr;

/** @template-implements Assertion<non-empty-string> */
final class IsAssignableToNullable implements Assertion
{
    /** @var Assertion<non-empty-string>|Assertion<class-string> */
    private Assertion $assertion;

    private string $type;

    public function __construct(string $type)
    {
        (new IsNullable())
            ->assert($type);

        $this->type      = substr($type, 1);
        $this->assertion = $this->getAssertion($this->type);
    }

    /** @psalm-assert non-empty-string $value */
    public function assert(mixed $value, string $message = ''): void
    {
        if (! $this($value)) {
            $message = sprintf(
                ! (new IsEmpty())($message) ?
                    $message : 'Expected type to be assignable to nullable type %2$s. Got %1$s.',
                Type::toLiteral($value),
                Type::toLiteral('?' . $this->type),
            );

            throw new AssertionFailed($message);
        }
    }

    /** @psalm-assert-if-true non-empty-string $value */
    public function __invoke(mixed $value): bool
    {
        (new IsType())
            ->assert($value);

        if ((new IsNullable())($value)) {
            return substr($value, 1) === $this->type;
        }

        return ($this->assertion)($value);
    }

    /** @return Assertion<non-empty-string>|Assertion<class-string> */
    private function getAssertion(string $type): Assertion
    {
        if ((new IsClassOrInterface())($type)) {
            /** @psalm-var class-string $type */
            return new IsAssignableToClass($type);
        }

        return new IsAssignableToPrimitive($type);
    }
}
