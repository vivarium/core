<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) 2026 Luca Cantoreggi
 */

namespace Vivarium\Container;

enum Scope
{
    case SERVICE;
    case CLONEABLE;
    case TRANSIENT;
}
