<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Home\Action;

use PhpFidder\Core\Components\Auth\Attributes\IsGranted;
use PhpFidder\Core\Components\Home\Request\HomeRequest;
use PhpFidder\Core\Components\Home\Response\HomeResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

#[IsGranted]
final class Home
{
    public function __invoke(ServerRequestInterface $httpRequest): ResponseInterface
    {
        $request = new HomeRequest($httpRequest);

        return new HomeResponse();
    }
}
