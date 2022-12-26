<?php
declare(strict_types=1);

namespace PhpFidder\Core\Repository;

use PhpFidder\Core\Entity\UserEntity;

final class PDOUserRepository implements UserRepository
{
    public function add(UserEntity $userEntity): bool
    {
        return true;
    }

    public function persist(): bool
    {
       return true;
    }

}