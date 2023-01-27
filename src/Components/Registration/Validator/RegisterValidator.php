<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Registration\Validator;

use PhpFidder\Core\Components\Core\AbstractValidator;
use PhpFidder\Core\Components\Core\ValidatorRequestInterface;
use PhpFidder\Core\Components\Registration\Request\RegisterRequest;

use function mb_strlen;

final class RegisterValidator extends AbstractValidator
{
    private array $errors = [];
    private bool $emailExists = false;
    private bool $usernameExists = false;
    public function validate(ValidatorRequestInterface $request): array
    {
        $username = $request->getUsername();
        $email = $request->getEmail();
        $password = $request->getPassword();
        $passwordRepeat = $request->getPassword();
        $errors = [];
        if (mb_strlen($username) === 0) {
            $errors[]='Username is empty';
        }
        if (mb_strlen($username) <= 3) {
            $errors[] = 'Username is too short';
        }
        if (mb_strlen($username) > 20) {
            $errors[] = 'Username is too long';
        }
        if ($this->usernameExists) {
            $errors[] = 'Username already registered';
        }
        if (mb_strlen($email) === 0) {
            $errors[]='Email is empty';
        }
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors[]= 'Email is invalid';
        }
        if ($this->emailExists) {
            $errors[]='Email already used';
        }
        if (mb_strlen($password) === 0) {
            $errors[]='Password is empty';
        }
        if (mb_strlen($password) < 8) {
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
