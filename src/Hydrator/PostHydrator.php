<?php

declare(strict_types=1);

namespace PhpFidder\Core\Hydrator;

use PhpFidder\Core\Entity\PostEntity;
use Ramsey\Uuid\Uuid;

final class PostHydrator
{
    public function hydrate(array $data): PostEntity
    {
        $id = $data['id'] ?? Uuid::uuid7()->getBytes();

        return new PostEntity($id, $data['userId'], $data['content']);
    }
}
