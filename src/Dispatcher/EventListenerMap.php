<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Dispatcher;

use ReflectionClass;
use Vivarium\Collection\Collection;
use Vivarium\Collection\MultiMap\MultiMap;
use Vivarium\Collection\MultiMap\MultiValueMap;
use Vivarium\Collection\Sequence\ArraySequence;
use Vivarium\Collection\Set\HashSet;

final class EventListenerMap implements EventListenerProvider, EventListenerSubscriber
{
    /** @var MultiMap<class-string<Event>, ListenerAndPriority<Event>> */
    private MultiMap $listeners;

    public function __construct()
    {
        $this->listeners = $this->createMap();
    }

    /**
     * @param class-string<Event> $event
     *
     * @return Collection<ListenerAndPriority<Event>>
     */
    public function provide(string $event): Collection
    {
        /** @var Collection<ListenerAndPriority<Event>> $listeners */
        $listeners = new ArraySequence();
        foreach ($this->collectClasses($event) as $class) {
            foreach ($this->listeners->get($class) as $listener) {
                $listeners = $listeners->add($listener);
            }
        }

        return $listeners;
    }

    /**
     * @param class-string<T>  $event
     * @param EventListener<T> $listener
     *
     * @template T of Event
     */
    public function subscribe(string $event, EventListener $listener, int|null $priority = null): self
    {
        if ($priority === null) {
            $priority = Priority::NORMAL;
        }

        $subscriber            = clone $this;
        $subscriber->listeners = $subscriber->listeners->put(
            $event,
            new ListenerAndPriority($listener, $priority),
        );

        return $subscriber;
    }

    /**
     * @param class-string<T>  $event
     * @param EventListener<T> $listener
     *
     * @template T of Event
     */
    public function unsubscribe(string $event, EventListener $listener, int|null $priority = null): self
    {
        if ($priority === null) {
            $priority = Priority::NORMAL;
        }

        $subscriber            = clone $this;
        $subscriber->listeners = $subscriber->listeners->remove(
            $event,
            new ListenerAndPriority($listener, $priority),
        );

        return $subscriber;
    }

    /** @return callable(): ArraySequence<ListenerAndPriority<Event> */
    private function ofListeners(): callable
    {
        return static function () {
            /**
             * phpcs:ignore
             * @var ArraySequence<ListenerAndPriority<Event>>
             */
            return new ArraySequence();
        };
    }

    /** @return MultiMap<class-string<Event>, ListenerAndPriority<Event>> */
    private function createMap(): MultiMap
    {
        /**
         * phpcs:ignore
         * @var MultiMap<class-string<Event>, ListenerAndPriority<Event>>
         */
        return new MultiValueMap(
            $this->ofListeners(),
        );
    }

    /**
     * @param class-string<T> $class
     *
     * @return Collection<class-string<T>>
     *
     * @template T as Event
     */
    private function collectClasses(string $class): Collection
    {
        $classes = new HashSet();

        $reflector = new ReflectionClass($class);

        while ($reflector !== false) {
            $classes = $classes->add($reflector->getName());
            foreach ($reflector->getInterfaceNames() as $interface) {
                $classes = $classes->add($interface);
            }

            $reflector = $reflector->getParentClass();
        }

        /**
         * phpcs:disable
         * @var Collection<class-string<T>>
         */
        return $classes;
    }
}
