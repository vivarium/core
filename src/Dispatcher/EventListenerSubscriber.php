<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Dispatcher;

interface EventListenerSubscriber
{
    /**
     * @param class-string<T>  $event
     * @param EventListener<T> $listener
     *
     * @template T of Event
     */
    public function subscribe(string $event, EventListener $listener, int|null $priority = null): self;

    /**
     * @param class-string<T>  $event
     * @param EventListener<T> $listener
     *
     * @template T of Event
     */
    public function unsubscribe(string $event, EventListener $listener, int|null $priority = null): self;
}
