<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Core;

use League\Event\EventDispatcher;
use PhpFidder\Core\Components\Landing\Event\IndexEvent;
use PhpFidder\Core\Components\Landing\Event\IndexListener;
use Psr\EventDispatcher\EventDispatcherInterface;

final class EventSubscriber
{
    /**
     * @param EventDispatcher $dispatcher
     */
    public function __construct(
        private readonly EventDispatcherInterface $dispatcher,
        private readonly array $listeners
    ) {
    }
    public function subscribeToEvents(): void
    {
        foreach ($this->listeners as $listener) {
            if (!$listener instanceof EventListenerInterface) {
                $message = sprintf('%s does not implements EventListenerInterface', get_class($listener));
                throw new \Exception($message);
            }
            $subscribedEvents = $listener->getSubscribedEvents();
            foreach ($subscribedEvents as $eventIdentifier => $method) {
                $this->dispatcher->subscribeTo($eventIdentifier, [$listener,$method]);
            }
        }
    }
}
