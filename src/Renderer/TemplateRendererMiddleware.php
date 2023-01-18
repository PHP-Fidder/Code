<?php
declare(strict_types=1);

namespace PhpFidder\Core\Renderer;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class TemplateRendererMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly TemplateRendererInterface $renderer){}
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $response = $handler->handle($request);

        if ($response instanceof RenderAwareInterface) {
            $body = $this->renderer->render($response->getTemplateName(),$response);
            $response = new Response();
            $response->getBody()->write($body);
            return $response;
        }

        return $response;
    }


}