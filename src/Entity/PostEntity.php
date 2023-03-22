<?php

declare(strict_types=1);

namespace PhpFidder\Core\Entity;

final class PostEntity
{
    public function __construct(
        private readonly string $id,
        private readonly string $userId,
        private readonly string $content
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}
