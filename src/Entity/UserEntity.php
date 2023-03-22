<?php

declare(strict_types=1);

namespace PhpFidder\Core\Entity;

final class UserEntity
{
    public function __construct(
        private readonly string $id,
        private readonly string $username,
        private readonly string $passwordHash,
        private readonly string $email
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
