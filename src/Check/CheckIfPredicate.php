<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

declare(strict_types=1);

namespace Vivarium\Check;

use Vivarium\Assertion\Boolean\IsFalse;
use Vivarium\Assertion\Boolean\IsTrue;

final class CheckIfPredicate
{
    public static function isTrue(bool $predicate): bool
    {
        return ((new IsTrue()))($predicate);
    }

    public static function isFalse(bool $predicate): bool
    {
        return ((new IsFalse()))($predicate);
    }
}
