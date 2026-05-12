<?php

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

declare(strict_types=1);

namespace Vivarium\Collection\Queue;

use Vivarium\Collection\Collection;

/**
 * @template T
 * @template-extends Collection<T>
 */
interface Queue extends Collection
{
    /**
     * @param T $element
     *
     * @return self<T>
     */
    public function enqueue($element): self;

    /** @return self<T> */
    public function dequeue(): self;

    /** @return T */
    public function peek();

    /**
     * @param T $element
     *
     * @return self<T>
     */
    public function add($element): self;

    /**
     * @param T $element
     *
     * @return self<T>
     */
    public function remove($element): self;

    /** @return self<T> */
    public function clear(): self;
}
