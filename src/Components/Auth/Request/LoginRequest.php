<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Auth\Request;

use PhpFidder\Core\Components\Core\ValidatorRequestInterface;
use Psr\Http\Message\ServerRequestInterface;

final class LoginRequest implements ValidatorRequestInterface
{
    private readonly string $username;
    private readonly string $password;
    private readonly string $method;
    private readonly array $errors;

    public function __construct(ServerRequestInterface $request)
    {
        $body = $request->getParsedBody();
        $this->method = $request->getMethod();
        if ($body !== null) {
            $this->username = $body['username'] ?? '';
            $this->password = $body['password'] ?? '';
        }
    }


    public function getUsername(): string
    {
        return $this->username;
    }


    public function getPassword(): string
    {
        return $this->password;
    }

    public function isPostRequest(): bool
    {
        return $this->method === 'POST';
    }

    public function withErrors(array $errors): self
    {
        $clone = clone $this;
        $clone->errors = $errors;
        return $clone;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
