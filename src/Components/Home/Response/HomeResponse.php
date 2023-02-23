<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Home\Response;

use Laminas\Diactoros\Response;
use PhpFidder\Core\Renderer\RenderAwareInterface;

final class HomeResponse extends Response implements RenderAwareInterface
{
    public function getTemplateName(): string
    {
        return 'home';
    }
}
