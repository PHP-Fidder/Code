<?php
declare(strict_types=1);

namespace PhpFidder\Core\Entity;

final class UserEntity
{
    public function __construct(private readonly string $id,
                                private readonly string $username,
                                private readonly string $passwordHash,
                                private readonly string $email
    )    {

    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}