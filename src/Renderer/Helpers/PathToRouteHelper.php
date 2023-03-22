<?php

declare(strict_types=1);

namespace PhpFidder\Core\Renderer\Helpers;

use League\Route\RouteCollectionInterface;

final class PathToRouteHelper implements MustacheHelperInterface
{
    public function __construct(private readonly RouteCollectionInterface $router)
    {
    }

    public function __invoke(string $content): string
    {
        $contentData = explode('|', $content);
        $routeName = array_shift($contentData);
        $replacementJson = array_shift($contentData);
        $replacement = null;
        if (null !== $replacementJson) {
            $replacement = json_decode($replacementJson);
        }
        $route = $this->router->getNamedRoute($routeName);

        return $route->getPath($replacement);
    }

    public function getName(): string
    {
        return 'pathToRoute';
    }
}
