<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Posts\Request;

use PhpFidder\Core\Components\Core\UserAwareRequest;
use Psr\Http\Message\ServerRequestInterface;

final class CreateRequest extends UserAwareRequest
{
    private string $content;

    public function __construct(ServerRequestInterface $httpRequest)
    {
        parent::__construct($httpRequest);
        $body = $httpRequest->getParsedBody();
        $this->content = $body['content'];
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'userId' => $this->getUser()->getId(),
        ];
    }
}
