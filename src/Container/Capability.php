<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) 2026 Luca Cantoreggi
 */

declare(strict_types=1);

namespace Vivarium\Container;

enum Capability
{
    case INJECTABLE;
    case INTERCEPTABLE;
    case DECORABLE;
}