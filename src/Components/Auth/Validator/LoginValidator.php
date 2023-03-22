<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Auth\Validator;

use PhpFidder\Core\Components\Auth\Request\LoginRequest;
use PhpFidder\Core\Components\Core\AbstractValidator;
use PhpFidder\Core\Components\Core\ValidatorRequestInterface;

final class LoginValidator extends AbstractValidator
{
    private bool $userNotExists = false;

    public function enablePasswordIsInvalidError(): void
    {
        $this->errors[] = 'Password is invalid';
    }

    /**
     * @param LoginRequest $request
     */
    public function validate(ValidatorRequestInterface $request): array
    {
        $username = $request->getUsername();
        $password = $request->getPassword();
        $usernameIsEmpty = 0 === mb_strlen($username);
        $errors = [];
        if ($usernameIsEmpty) {
            $errors[] = 'Username is empty';
        }
        if (false === $usernameIsEmpty
            && $this->userNotExists
        ) {
            $errors[] = 'User not found';
        }
        if (0 === mb_strlen($password)) {
            $errors[] = 'Password is empty';
        }

        return $errors;
    }

    public function enableUserNotExistsError(): void
    {
        $this->userNotExists = true;
    }
}
