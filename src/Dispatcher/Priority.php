<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Dispatcher;

interface Priority
{
    public const VERY_LOW = -100;

    public const LOW = -50;

    public const NORMAL = 0;

    public const HIGH = 50;

    public const VERY_HIGH = 100;
}
