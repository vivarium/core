<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Dispatcher;

use Vivarium\Collection\Collection;

interface EventListenerProvider
{
    /**
     * @param class-string<Event> $event
     *
     * @return Collection<ListenerAndPriority<Event>>
     */
    public function provide(string $event): Collection;
}
