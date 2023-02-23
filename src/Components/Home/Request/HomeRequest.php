<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Home\Request;

use Laminas\Session\Container;
use Psr\Http\Message\RequestInterface;

final class HomeRequest
{
    public function __construct(RequestInterface $httpRequest)
    {
    }
}
