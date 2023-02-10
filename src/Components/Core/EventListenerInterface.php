<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Core;

interface EventListenerInterface
{
    public function getSubscribedEvents(): array;
}
