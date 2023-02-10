<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Landing\Action;

use PhpFidder\Core\Components\Landing\Event\IndexEvent;
use PhpFidder\Core\Components\Landing\Request\IndexRequest;
use PhpFidder\Core\Components\Landing\Response\IndexResponse;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Index
{
    public function __construct(private readonly EventDispatcherInterface $dispatcher)
    {
    }

    public function __invoke(ServerRequestInterface $httpRequest): ResponseInterface
    {
        $request = new IndexRequest($httpRequest);
        $welcomeMessage = 'Hallo Welt';
        $event = new IndexEvent($welcomeMessage);
        $this->dispatcher->dispatch($event);
        return new IndexResponse($event->getWelcomeMessage());
    }
}
