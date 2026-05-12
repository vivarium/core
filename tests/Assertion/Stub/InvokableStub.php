<?php

declare(strict_types=1);

namespace Vivarium\Test\Assertion\Stub;

interface InvokableStub
{
    public function __invoke(): int;
}
