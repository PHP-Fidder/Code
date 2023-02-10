<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Landing\Event;

use PhpFidder\Core\Components\Core\EventListenerInterface;

final class IndexListener implements EventListenerInterface
{
    public function getSubscribedEvents(): array
    {
        return [
            IndexEvent::class => 'onIndexEvent',
        ];
    }

    public function onIndexEvent(IndexEvent $event): void
    {
        $event->setMessage('Willkommen vom Listener');
    }
}
