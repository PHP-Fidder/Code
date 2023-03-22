<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Core;

use PhpFidder\Core\Components\Auth\Middleware\AuthenticationMiddleware;
use PhpFidder\Core\Entity\UserEntity;
use Psr\Http\Message\ServerRequestInterface;

abstract class UserAwareRequest
{
    protected ServerRequestInterface $httpRequest;

    public function __construct(ServerRequestInterface $httpRequest)
    {
        $this->httpRequest = $httpRequest;
    }

    public function getUser(): UserEntity
    {
        return $this->httpRequest->getAttribute(AuthenticationMiddleware::ATTRIBUTE_USER);
    }
}
