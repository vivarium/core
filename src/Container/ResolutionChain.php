<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) 2026 Luca Cantoreggi
 */

namespace Vivarium\Container;

use Closure;
use Iterator;
use RuntimeException;
use Vivarium\Collection\Map\HashMap;
use Vivarium\Collection\Map\Map;
use Vivarium\Collection\Queue\PriorityQueue;
use Vivarium\Comparator\SortableComparator;
use Vivarium\Comparator\ValueAndPriority;

final class ResolutionChain implements Container
{
    /** @var PriorityQueue<ValueAndPriority<ResolutionNode>> */
    private PriorityQueue $nodes;

    /** @var Map<string, Definition> */
    private Map $definitions;

    public function __construct()
    {
        $this->nodes       = new PriorityQueue(new SortableComparator());
        $this->definitions = new HashMap();
    }

    public function get(Binding $binding): mixed
    {
        $definition = $this->resolve($binding);

        return $definition->solve($this);
    }

    public function has(Binding $binding): bool
    {
        try {
            $this->resolve($binding);

            return true;
        } catch (RuntimeException) {
            return false;
        }
    }

    public function withNode(ResolutionNode $node, int $priority): self
    {
        $chain = clone $this;

        $chain->nodes = $chain->nodes->enqueue(
            new ValueAndPriority($node, $priority)
        );

        return $chain;
    }

    private function resolve(Binding $binding): Definition
    {
        $hash = $binding->hash();

        if ($this->definitions->containsKey($hash)) {
            return $this->definitions->get($hash);
        }

        $iterator = $this->nodes->getIterator();
        $next     = $this->buildNext($binding, $iterator);

        $definition = $next();

        $this->definitions = $this->definitions->put($hash, $definition);

        return $definition;
    }

    /**
     * @param Iterator<ValueAndPriority<ResolutionNode>> $iterator
     *
     * @return Closure(): Definition
     */
    private function buildNext(Binding $binding, Iterator $iterator): Closure
    {
        if (! $iterator->valid()) {
            return static function () use ($binding): never {
                throw new RuntimeException(
                    "No binding found for {$binding->getType()}"
                );
            };
        }

        $wrapper = $iterator->current();
        $iterator->next();

        $node = $wrapper->getValue();
        $next = $this->buildNext($binding, $iterator);

        return fn (): Definition => $node->resolve($binding, $next);
    }
}
