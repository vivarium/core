<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MPL-2.0
 * Copyright (c) The Vivarium Project
 */

namespace Vivarium\Dispatcher;

final class NoOpDispatcher implements EventDispatcher
{
    /**
     * @param T $event
     *
     * @return T
     *
     * @template T as Event
     */
    public function dispatch(Event $event)
    {
        return $event;
    }
}
