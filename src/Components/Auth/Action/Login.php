<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Auth\Action;

use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Session\Container;
use PhpFidder\Core\Components\Core\PasswordHasherInterface;
use PhpFidder\Core\Components\Auth\Event\LoginSuccessEvent;
use PhpFidder\Core\Components\Auth\Request\LoginRequest;
use PhpFidder\Core\Components\Auth\Response\LoginResponse;
use PhpFidder\Core\Components\Auth\Validator\LoginValidator;
use PhpFidder\Core\Repository\UserRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Login
{
    public function __construct(
        private readonly LoginValidator $validator,
        private readonly UserRepository $userRepository,
        private readonly PasswordHasherInterface $passwordHasher,
        private readonly Container $session,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $loginRequest = new LoginRequest($request);

        $userExists = $this->userRepository->usernameExists($loginRequest->getUsername());

        if ($userExists === false) {
            $this->validator->enableUserNotExistsError();
        }

        if ($loginRequest->isPostRequest() && $this->validator->isValid($loginRequest)) {
            $user = $this->userRepository->findByUsername($loginRequest->getUsername());
            $passwordIsValid = $this->passwordHasher->isValid($loginRequest->getPassword(), $user->getPasswordHash());
            if ($passwordIsValid) {
                $this->session->userId = $user->getId();
                $event = new LoginSuccessEvent($user);
                $this->dispatcher->dispatch($event);
                return new RedirectResponse('/');
            }
            $this->validator->enablePasswordIsInvalidError();
        }
        $loginRequest = $loginRequest->withErrors($this->validator->getErrors());
        return new LoginResponse($loginRequest);
    }
}
