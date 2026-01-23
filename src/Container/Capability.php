<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MIT
 * Copyright (c) 2025 Luca Cantoreggi
 */

declare(strict_types=1);

namespace Vivarium\Container;

enum Capability
{
    case INJECTABLE;
    case INTERCEPTABLE;
    case DECORABLE;
}