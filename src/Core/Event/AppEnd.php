<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) 2026 Luca Cantoreggi
 */

namespace Vivarium\Core\Event;

use Vivarium\Assertion\Numeric\IsGreaterOrEqualThan;
use Vivarium\Dispatcher\NonStoppableEvent;

final class AppEnd extends NonStoppableEvent
{
    private int $exitCode;

    public function __construct(int $exitCode)
    {
        (new IsGreaterOrEqualThan(0))
            ->assert($exitCode);

        $this->exitCode = $exitCode;
    }

    public function getExitCode(): int
    {
        return $this->exitCode;
    }
}
