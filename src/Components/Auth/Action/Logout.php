<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Auth\Action;

use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Session\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Logout
{
    public function __construct(
        private readonly Container $session,
    ) {
    }

    public function __invoke(ServerRequestInterface $httpRequest): ResponseInterface
    {
        unset($this->session->userId);
        $this->session->flashMessage[] = 'Logout successfull';

        return new RedirectResponse('/');
    }
}
