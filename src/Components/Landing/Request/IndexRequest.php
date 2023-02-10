<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Landing\Request;

use Psr\Http\Message\ServerRequestInterface;

final class IndexRequest
{
    public function __construct(ServerRequestInterface $request)
    {
    }
}
