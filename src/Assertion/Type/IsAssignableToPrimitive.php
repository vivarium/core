<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

declare(strict_types=1);

namespace Vivarium\Assertion\Type;

use ReflectionClass;
use Vivarium\Assertion\Assertion;
use Vivarium\Assertion\Exception\AssertionFailed;
use Vivarium\Assertion\String\IsEmpty;
use Vivarium\Type\Type;

use function sprintf;

/** @template-implements Assertion<non-empty-string> */
final class IsAssignableToPrimitive implements Assertion
{
    public function __construct(private string $primitive)
    {
        (new IsPrimitive())
            ->assert($primitive);
    }

    /** @psalm-assert non-empty-string $value */
    public function assert(mixed $value, string $message = ''): void
    {
        if (! $this($value)) {
            $message = sprintf(
                ! (new IsEmpty())($message) ?
                    $message : 'Expected type to be assignable to primitive type %2$s. Got %1$s.',
                Type::toLiteral($value),
                Type::toLiteral($this->primitive),
            );

            throw new AssertionFailed($message);
        }
    }

    /** @psalm-assert-if-true non-empty-string $value */
    public function __invoke(mixed $value): bool
    {
        (new IsBasicType())
            ->assert($value);

        if ($this->primitive === Type::MIXED) {
            return true;
        }

        if ((new IsPrimitive())($value)) {
            if ($this->primitive === Type::FLOAT && $value === Type::INT) {
                return true;
            }

            return $this->primitive === $value;
        }

        /** @psalm-var class-string $value */

        if ($this->primitive === Type::OBJECT) {
            return true;
        }

        if ($this->primitive === Type::STRING) {
            return (new ReflectionClass($value))
                ->hasMethod('__toString');
        }

        if ($this->primitive === Type::CALLABLE) {
            return (new ReflectionClass($value))
                ->hasMethod('__invoke');
        }

        return false;
    }
}
