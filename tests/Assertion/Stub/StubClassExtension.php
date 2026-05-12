<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

declare(strict_types=1);

namespace Vivarium\Test\Assertion\Stub;

final class StubClassExtension extends StubClass implements InvokableStub
{
    public function __toString(): string
    {
        return 'StubClassExtension';
    }

    public function __invoke(): int
    {
        return 42;
    }
}
