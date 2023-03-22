<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Posts\Response;

use Laminas\Diactoros\Response;
use PhpFidder\Core\Components\Posts\Request\CreateRequest;
use PhpFidder\Core\Renderer\RenderAwareInterface;

final class CreateResponse extends Response implements RenderAwareInterface
{
    public string $content;

    public function __construct(CreateRequest $request)
    {
        $this->content = $request->getContent();
    }

    public function getTemplateName(): string
    {
        return 'home';
    }
}
