<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Registration\Action;

use Laminas\Diactoros\Response;
use PhpFidder\Core\Components\Core\PasswordHasherInterface;
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
        private readonly PasswordHasherInterface $passwordHasher,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $registerRequest = new RegisterRequest($request);

        $usernameExists = $this->userRepository->usernameExists($registerRequest->getUsername());
        if ($usernameExists) {
            $this->validator->enableUsernameExistsError();
        }
        $emailExists = $this->userRepository->emailExists($registerRequest->getEmail());
        if ($emailExists) {
            $this->validator->enableEmailExistsError();
        }

        if ($registerRequest->isPostRequest()
            && $this->validator->isValid($registerRequest)) {
            $userArray = $registerRequest->toArray();
            $userArray['passwordHash'] = $this->passwordHasher->hash($registerRequest->getPassword());
            $user = $this->userHydrator->hydrate($userArray);
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
