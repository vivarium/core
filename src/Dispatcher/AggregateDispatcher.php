<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Dispatcher;

use Vivarium\Collection\Sequence\ArraySequence;
use Vivarium\Collection\Sequence\Sequence;

use function array_merge;

final class AggregateDispatcher implements EventDispatcher
{
    /** @var Sequence<EventListenerProvider> */
    private Sequence $providers;

    /** @no-named-arguments */
    public function __construct(EventListenerProvider $provider, EventListenerProvider ...$providers)
    {
        $this->providers = new ArraySequence(...array_merge([$provider], $providers));
    }

    /**
     * @param T $event
     *
     * @return T
     *
     * @template T as Event
     */
    public function dispatch(Event $event)
    {
        /** @var Sequence<ListenerAndPriority<Event>> $listeners */
        $listeners = new ArraySequence();
        foreach ($this->providers as $provider) {
            foreach ($provider->provide($event::class) as $listener) {
                $listeners = $listeners->add($listener);
            }
        }

        $listeners = $listeners->sort(new ListenerAndPriorityComparator());
        foreach ($listeners as $listener) {
            if ($event->isPropagationStopped()) {
                break;
            }

            /**
             * phpcs:ignore
             * @var T $event
             */
            $event = $listener->getEventListener()->handle($event);
        }

        return $event;
    }
}
