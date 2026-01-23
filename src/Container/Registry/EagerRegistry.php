<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MIT
 * Copyright (c) 2025 Luca Cantoreggi
 */

namespace Vivarium\Container;

use Vivarium\Collection\Map\HashMap;
use Vivarium\Collection\MultiMap\MultiMap;
use Vivarium\Collection\MultiMap\MultiValueMap;
use Vivarium\Collection\Queue\PriorityQueue;
use Vivarium\Comparator\SortableComparator;
use Vivarium\Comparator\ValueAndPriority;

final class EagerRegistry implements ConfigurableRegistry
{
    /** @var HashMap<Binding, Provider> */
    private HashMap $providers;

    /** @var HashMap<Binding, Binding> */
    private HashMap $chains;

    /** @var MultiMap<Binding, Queue<Enhancement>> */
    private MultiMap $enhancements;

    public function __construct()
    {
        $this->providers    = new HashMap();
        $this->chains       = new HashMap();
        $this->enhancements = new MultiValueMap(function () {
            return new PriorityQueue(
                new SortableComparator()
            );
        });
    }

    public function withProvider(Binding $source, Provider $provider) : self
    {
        $registry = clone $this;
        $registry->providers = $registry->providers->put($source, $provider);

        return $registry;
    }

    public function withChain(Binding $source, Binding $target) : self
    {
        $registry = clone $this;
        $registry->chains = $registry->chains->put($source, $target);

        return $registry;
    }

    public function withEnhacement(Binding $target, Enhancement $enhancement, int $priority) : self
    {
        $registry = clone $this;
        $registry->enhancements = $registry->enhancements->put(
            $enhancement,
            new ValueAndPriority($enhancement, $priority)
        );

        return $registry;
    }
}
