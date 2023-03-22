<?php

declare(strict_types=1);

namespace PhpFidder\Core\Repository;

use PhpFidder\Core\Entity\PostEntity;

interface PostRepository
{
    public function add(PostEntity $post): bool;

    public function persist(): bool;
}
