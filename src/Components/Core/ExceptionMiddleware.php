<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Core;

use Laminas\Diactoros\Response\RedirectResponse;
use PhpFidder\Core\Components\Auth\Exception\LoginRequiredException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ExceptionMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            // Allgemeiner prozess
            return $handler->handle($request);
        } catch (LoginRequiredException $e) {
            // Login Required Fehler
            return new RedirectResponse('/account/login');
        } catch (\Throwable $e) {
            // TODO Error Page response
            // Allgemeiner Fehler
            throw $e;
        }
    }
}
