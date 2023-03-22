<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Registration\Validator;

use PhpFidder\Core\Components\Core\AbstractValidator;
use PhpFidder\Core\Components\Core\ValidatorRequestInterface;

final class RegisterValidator extends AbstractValidator
{
    private bool $emailExists = false;
    private bool $usernameExists = false;

    public function validate(ValidatorRequestInterface $request): array
    {
        $username = $request->getUsername();
        $email = $request->getEmail();
        $password = $request->getPassword();
        $passwordRepeat = $request->getPassword();
        $errors = [];
        if (0 === \mb_strlen($username)) {
            $errors[] = 'Username is empty';
        }
        if (\mb_strlen($username) <= 3) {
            $errors[] = 'Username is too short';
        }
        if (\mb_strlen($username) > 20) {
            $errors[] = 'Username is too long';
        }
        if ($this->usernameExists) {
            $errors[] = 'Username already registered';
        }
        if (0 === \mb_strlen($email)) {
            $errors[] = 'Email is empty';
        }
        if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email is invalid';
        }
        if ($this->emailExists) {
            $errors[] = 'Email already used';
        }
        if (0 === \mb_strlen($password)) {
            $errors[] = 'Password is empty';
        }
        if (\mb_strlen($password) < 8) {
            $errors[] = 'Password is too short';
        }
        if ($password !== $passwordRepeat) {
            $errors[] = 'Password repeat does not match password';
        }

        return $errors;
    }

    public function enableEmailExistsError(): void
    {
        $this->emailExists = true;
    }

    public function enableUsernameExistsError(): void
    {
        $this->usernameExists = true;
    }
}
