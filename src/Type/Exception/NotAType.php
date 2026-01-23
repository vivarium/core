<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MIT
 * Copyright (c) 2024 Luca Cantoreggi
 */

namespace Vivarium\Type\Exception;

use DomainException;
use Vivarium\Type\Type;

use function sprintf;

final class NotAType extends DomainException
{
    public function __construct(string $type)
    {
        parent::__construct(
            sprintf('Expected a valid type. Got %s.', Type::toLiteral($type))
        );
    }
}
