<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) 2026 Luca Cantoreggi
 */

namespace Vivarium\Container\Definition;

use Vivarium\Collection\Queue\PriorityQueue;
use Vivarium\Comparator\SortableComparator;
use Vivarium\Comparator\ValueAndPriority;
use Vivarium\Container\Container;
use Vivarium\Container\Definition;
use Vivarium\Container\Enhancement;
use Vivarium\Container\Provider;

final class Transient implements Definition
{
    private PriorityQueue $enhancements;

    public function __construct(private Provider $provider)
    {
        $this->enhancements = new PriorityQueue(
            new SortableComparator(),
        );
    }

    public function solve(Container $container): mixed
    {
        $instance = $this->provider->provide($container);

        foreach ($this->enhancements as $enhancement) {
            $instance = $enhancement
                ->getValue()
                ->enhance($instance, $container);
        }

        return $instance;
    }

    public function withEnhancement(Enhancement $enhancement, int $priority): self
    {
        $transient = clone $this;

        $transient->enhancements = $transient->enhancements->enqueue(
            new ValueAndPriority(
                $enhancement,
                $priority,
            ),
        );

        return $transient;
    }
}
