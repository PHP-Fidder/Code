<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Auth\Validator;

use PhpFidder\Core\Components\Core\AbstractValidator;
use PhpFidder\Core\Components\Core\ValidatorRequestInterface;
use PhpFidder\Core\Components\Auth\Request\LoginRequest;

final class LoginValidator extends AbstractValidator
{
    private bool $userNotExists = false;
    public function enablePasswordIsInvalidError(): void
    {
        $this->errors[]='Password is invalid';
    }

    /**
     * @param LoginRequest $request
     * @return array
     */
    public function validate(ValidatorRequestInterface $request): array
    {
        $username = $request->getUsername();
        $password = $request->getPassword();
        $usernameIsEmpty = mb_strlen($username) === 0;
        $errors = [];
        if ($usernameIsEmpty) {
            $errors[] = 'Username is empty';
        }
        if ($usernameIsEmpty === false &&
            $this->userNotExists
        ) {
            $errors[] = 'User not found';
        }
        if (mb_strlen($password) === 0) {
            $errors[]='Password is empty';
        }
        return $errors;
    }

    public function enableUserNotExistsError(): void
    {
        $this->userNotExists = true;
    }
}
