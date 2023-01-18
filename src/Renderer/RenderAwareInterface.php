<?php
declare(strict_types=1);

namespace PhpFidder\Core\Renderer;

interface RenderAwareInterface
{
    public function getTemplateName():string;
}