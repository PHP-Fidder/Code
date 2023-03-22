<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Registration\Request;

use PhpFidder\Core\Components\Core\ValidatorRequestInterface;
use Psr\Http\Message\ServerRequestInterface;

final class RegisterRequest implements ValidatorRequestInterface
{
    private readonly string $username;
    private readonly string $password;
    private readonly string $email;
    private readonly string $passwordRepeat;
    private readonly string $method;
    private readonly array $errors;

    public function __construct(ServerRequestInterface $request)
    {
        $body = $request->getParsedBody();
        $this->method = $request->getMethod();
        if (null !== $body) {
            $this->username = $body['username'] ?? '';
            $this->email = $body['email'] ?? '';
            $this->password = $body['password'] ?? '';
            $this->passwordRepeat = $body['passwordRepeat'] ?? '';
        }
    }

    public function toArray(): array
    {
        return [
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'passwordRepeat' => $this->passwordRepeat,
        ];
    }

    public function isPostRequest(): bool
    {
        return 'POST' === strtoupper($this->method);
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordRepeat(): string
    {
        return $this->passwordRepeat;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function withErrors(array $errors): self
    {
        $clone = clone $this;
        $clone->errors = $errors;

        return $clone;
    }
}
