<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Posts\Action;

use Laminas\Diactoros\Response\RedirectResponse;
use PhpFidder\Core\Components\Auth\Attributes\IsGranted;
use PhpFidder\Core\Components\Posts\Request\CreateRequest;
use PhpFidder\Core\Components\Posts\Response\CreateResponse;
use PhpFidder\Core\Hydrator\PostHydrator;
use PhpFidder\Core\Repository\PostRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

#[IsGranted]
final class Create
{
    public function __construct(
        private readonly PostHydrator $postHydrator,
        private readonly PostRepository $postRepository
    ) {
    }

    public function __invoke(ServerRequestInterface $httpRequest): ResponseInterface
    {
        $request = new CreateRequest($httpRequest);

        $postEntry = $this->postHydrator->hydrate($request->toArray());
        $this->postRepository->add($postEntry);
        if ($this->postRepository->persist()) {
            return new RedirectResponse('/home');
        }

        return new CreateResponse($request);
    }
}
