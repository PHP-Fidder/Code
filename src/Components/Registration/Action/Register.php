<?php
declare(strict_types=1);

namespace PhpFidder\Core\Components\Registration\Action;

use Laminas\Diactoros\Response;
use PhpFidder\Core\Components\Registration\Event\RegistrationSuccessEvent;
use PhpFidder\Core\Components\Registration\Request\RegisterRequest;
use PhpFidder\Core\Components\Registration\Response\RegisterResponse;
use PhpFidder\Core\Components\Registration\Validator\RegisterValidator;
use PhpFidder\Core\Hydrator\UserHydrator;
use PhpFidder\Core\Repository\UserRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Register
{
    public function __construct(
    private readonly RegisterValidator $validator,
    private readonly UserHydrator $userHydrator,
    private readonly UserRepository $userRepository,
    private readonly EventDispatcherInterface $dispatcher
    ){

    }
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $registerRequest = new RegisterRequest($request);

        $usernameExists = $this->userRepository->usernameExists($registerRequest->getUsername());
        $emailExists = $this->userRepository->emailExists($registerRequest->getEmail());


        if($registerRequest->isPostRequest() && $this->validator->isValid($registerRequest,
                $usernameExists,
                $emailExists
            ))
        {
            $user = $this->userHydrator->hydrate($registerRequest->toArray());
            $this->userRepository->add($user);
            $this->userRepository->persist();

            $event = new RegistrationSuccessEvent($user);
            $this->dispatcher->dispatch($event);

            return new Response\RedirectResponse('/');
        }

        $registerRequest = $registerRequest->withErrors($this->validator->getErrors());

        return new RegisterResponse($registerRequest);
    }
}