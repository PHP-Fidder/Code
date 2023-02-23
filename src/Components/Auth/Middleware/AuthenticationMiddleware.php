<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Auth\Middleware;

use Laminas\Session\Container;
use League\Route\Route;
use PhpFidder\Core\Components\Auth\Attributes\IsGranted;
use PhpFidder\Core\Components\Auth\Exception\LoginRequiredException;
use PhpFidder\Core\Entity\UserEntity;
use PhpFidder\Core\Repository\UserRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AuthenticationMiddleware implements MiddlewareInterface
{
    public const ATTRIBUTE_USER = 'user';

    public function __construct(
        private readonly ContainerInterface $container,
        private readonly Container          $session,
        private readonly UserRepository     $userRepository
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeCollection = $handler->getMiddlewareStack();
        /** @var Route $route */
        $route = array_pop($routeCollection);

        $callable = $route->getCallable($this->container);

        $reflection = new \ReflectionClass($callable);
        $loginRequiredAttribute = $reflection->getAttributes(IsGranted::class);
        $hasLoginRequiredAttribute = count($loginRequiredAttribute) > 0;

        if ($hasLoginRequiredAttribute) {
            if (!isset($this->session->userId)) {
                throw new LoginRequiredException("Login Required");
            }
            try {
                $user = $this->userRepository->findById((string)$this->session->userId);
            } catch (\Throwable $e) {
                throw new LoginRequiredException($e->getMessage());
            }
            $request = $request->withAttribute(self::ATTRIBUTE_USER, $user);
        }

        return $handler->handle($request);
    }
}
